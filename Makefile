SHELL=bash
# advent of code helpers
# looks in src/ for any Day[N].php files, sorts for the highest and sets that value
# When you move to a new day you would create the DayN.php file then run `make get-input`
# to retrieve that input, storing it in ./input/day[N].txt
# saves time
OS_NAME := $(shell uname -s | tr A-Z a-z)
latestDay :=$(shell if [[ "$(OS_NAME)" == "linux" ]]; then find src -maxdepth 1 -type f  \( -name "Day[0-9][0-9].php" -o -name "Day[0-9].php" \) -printf '%f\n' | sort -Vr | head -1 | grep -o '[0-9]\+' || echo "1";  else find src -maxdepth 1 -type f  \( -name "Day[0-9][0-9].php" -o -name "Day[0-9].php" \) -print0 | xargs -0 stat -f '%N ' | sort -Vr | head -1 | grep -o '[0-9]\+' || echo "1"; fi)
# in order to retrieve the Days input from the server you must login to adventofcode.com and grab the `session` cookie
# then set export AOC_COOKIE=53616c7465645f5f2b44c4d4742765e14...
aocCookie :=$(AOC_COOKIE)

# append day={N} to make commands to run just that day
ifdef day
	onlyThisDay :=$$day
else
	onlyThisDay :=
endif
# append part={N} to make commands to run just that part
ifdef part
	onlyThisPart :=$$part
else
	onlyThisPart :=
endif
onlyThis:=$(onlyThisDay) $(onlyThisPart)

help: ## This help.
	@printf "\033[32m---------------------------------------------------------------------------\n  Advent of Code 2020 - James Thatcher\n  Current Day:\033[33m $(latestDay)\033[32m\n---------------------------------------------------------------------------\033[0m\n"
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

.DEFAULT_GOAL := help
.PHONY: tests

# basic vars
image-name :=aoc-2020
php-image  :=php:8-cli
uid        :=$(shell id -u)
gid        :=$(shell id -g)

# define our reusable docker run commands
# php -dxdebug.mode=off -dzend_extension=opcache.so -dopcache.enable_cli=1 -dopcache.jit_buffer_size=100M -dopcache.jit=1255 run.php $(onlyThisDay)

# For Day13 I needed the gmp library, so I made my own docker image based on php:8-cli (see Dockerfile)
define DOCKER_RUN_PHP_MY_IMAGE
docker run -it --rm --init \
	--name "$(image-name)" \
	-u "$(uid):$(gid)" \
	-v "$(PWD):/app" \
	-e PHP_IDE_CONFIG="serverName=$(image-name)" \
	-w /app
endef

define DOCKER_RUN_PHP
docker run -it --rm --init \
	--name "$(image-name)" \
	-u "$(uid):$(gid)" \
	-v "$(PWD):/app" \
	-w /app \
	"$(php-image)"
endef

define DOCKER_RUN_PHP_XDEBUG
docker run -it --rm --init \
	--name "$(image-name)-xdebug" \
	--network=host\
	-u "$(uid):$(gid)" \
	-e PHP_IDE_CONFIG="serverName=$(image-name)" \
	-v "$(PWD):/app" \
	-v "$(PWD)/xdebug.ini:/usr/local/etc/php/conf.d/xdebug.ini" \
	-w /app \
	mileschou/xdebug:8.0
endef

define DOCKER_RUN_COMPOSER
docker run --rm -it \
	--name "$(image-name)-composer" \
	-u "$(uid):$(gid)" \
	-v "$(PWD):/app" \
	-v "/tmp:/tmp" \
	-w /app \
	composer
endef

run: ## runs each days solution without test framework
ifeq ($(shell docker image inspect $(image-name) > /dev/null 2>&1 || echo not_exists), not_exists)
	@echo -e "\nFirst run detected! No $(image-name) docker image found, running docker build...\n"
	DOCKER_BUILDKIT=1 docker build --build-arg UID=$(shell id -u) --build-arg GID=$(shell id -g) \
		--tag="$(image-name)" \
		-f Dockerfile .
	make run
else
ifneq ("$(wildcard vendor)", "")
	@$(DOCKER_RUN_PHP_MY_IMAGE) $(image-name) php -dopcache.enable_cli=1 -dopcache.jit_buffer_size=100M -dopcache.jit=1255 run.php $(onlyThis)
else
	@echo -e "\nFirst run detected! No vendor/ folder found, running composer update...\n"
	make composer
	make run
endif
endif

tests: ## runs each days pest tests within a docker container
ifeq ($(shell docker image inspect $(image-name) > /dev/null 2>&1 || echo not_exists), not_exists)
	@echo -e "\nFirst run detected! No $(image-name) docker image found, running docker build...\n"
	DOCKER_BUILDKIT=1 docker build --build-arg UID=$(shell id -u) --build-arg GID=$(shell id -g) \
		--tag="$(image-name)" \
		-f Dockerfile .
	make tests
else
ifneq ("$(wildcard vendor)", "")
	$(DOCKER_RUN_PHP_MY_IMAGE) $(image-name) vendor/bin/pest --testdox
else
	@echo -e "\nFirst run detected! No vendor/ folder found, running composer update...\n"
	make composer
	make tests
endif
endif

composer: ## Runs `composer update` on CWD, specify other commands via cmd=
ifdef cmd
	$(DOCKER_RUN_PHP_MY_IMAGE) $(image-name) composer --no-cache $(cmd)
else
	$(DOCKER_RUN_PHP_MY_IMAGE) $(image-name) composer --no-cache update
endif

shell: ## Launch a shell into the docker container
	$(DOCKER_RUN_PHP_MY_IMAGE) $(image-name) /bin/bash

xdebug: ## Launch a php container with xdebug (port 10000)
	@$(DOCKER_RUN_PHP_MY_IMAGE) -e XDEBUG_MODE=debug $(image-name) php run.php $(onlyThis)

xdebug-profile: ## Runs the xdebug profiler for analysing performance
	$(DOCKER_RUN_PHP_MY_IMAGE) -e XDEBUG_MODE=profile $(image-name) php -dxdebug.output_dir=/app run.php $(onlyThis)

cleanup: ## remove all docker images
	docker rm $$(docker ps -a | grep '$(image-name)' | awk '{print $$1}') --force || true
	docker image rm $(image-name)

cs-fix: ## run php-cs-fixer
	$(DOCKER_RUN_PHP_MY_IMAGE) $(image-name) composer --no-cache run cs-fixer

phpstan: ## run phpstan
	$(DOCKER_RUN_PHP_MY_IMAGE) $(image-name) composer --no-cache run phpstan

get-input: ## Retrieves the latest day's input from server
ifndef aocCookie
	@echo -e "Missing AOC_COOKIE env\n\nPlease login to https://adventofcode.com/ and retrieve your session cookie."
	@echo -e "Then set the environmental variable AOC_COOKIE. e.g. export AOC_COOKIE=53616c7465645f5f2b44c4d4742765e14...\n"
else
	@echo -e "Fetching latest input using day=$(latestDay) AOC_COOKIE=$(aocCookie)"
	@curl -s --location --request GET 'https://adventofcode.com/2020/day/$(latestDay)/input' --header 'Cookie: session=$(aocCookie)' -o ./input/day$(latestDay).txt && echo "./src/day$(latestDay).txt downloaded" || echo "error downloading"
endif