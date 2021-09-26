## Advent of Code PHP 2020 
>The solutions to advent of code 2020 in php, specifically PHP8/Pest, by [James Thatcher](http://github.com/jthatch)

### Solutions
> 🎄 [Day 1](/src/Day1.php) 🎅 [Day 2](/src/Day2.php) ☃️ [Day 3](/src/Day3.php) 🦌 [Day 4](/src/Day4.php) 🍪 [Day 5](/src/Day5.php) 
> 🥛 [Day 6](/src/Day6.php) 🧦 [Day 7](/src/Day7.php) 🎁 [Day 8](/src/Day8.php)   
> ⛄ [Day 9](/src/Day9.php) 🛐 [Day 10](/src/Day10.php)

### About

As mentioned this is my attempt at tacking the awesome challenges at [Advent of Code 2020](https://adventofcode.com/2020/day/1) using PHP8.

You will find the solutions in the [/src](/src) directory and if you're feeling cheeky, the [/tests](/tests) folder
has your expected answers to each day. The raw inputs are stored in [/input](/input), fetched automatically via the `make day` command.

Included in this repo is also a handy [Makefile](/Makefile) that launches a php docker container to execute the tests.

If you fancy having a go at the challenges yourself feel free to use this repo as a framework/skeleton.

It has many helpful features to make tackling each Days' challenge as easy as possible, as well as commands like `make day` 
that will automatically fetch the input from [adventofcode](https://adventofcode.com)'s servers along with an xdebug instance for debugging those particularly difficult challenges.

### Instructions
- **Run all days**  
 `make tests`  -- run within the Pest framework  
 `make run`  -- run without a framework
  
  
- **Use XDebug**  
  `make xdebug` at the shell type: `vendor/bin/pest`  
  IDE settings: 
  - `10000` - xdebug port 
  - `aoc-2020` - PHP_IDE_CONFIG (what you put in PHPStorm -> settings -> debug -> server -> name)
  - `/app` - absolute path on the server  
  - see [xdebug.ini](/xdebug.ini) if you're stuck


- **Fetch the next days input from the server.**  
  `make day`  
  _Note: The Makefile reads the [/src](/src) directory to find the most recent DayN.php file. If you had just completed `Day1.php` you would create a `Day2.php` and then run this command to fetch `/input/day2.txt`_

### Full make commands
```shell
$ make
#---------------------------------------------------------------------------
# Advent of Code 2020 - James Thatcher
# Current Day: N
#---------------------------------------------------------------------------
help                           This help.
tests                          runs each days pest tests within a docker container
composer                       Runs `composer update` on CWD, specify other commands via cmd=
shell                          Launch a shell into the docker container
xdebug                         Launch a php container with xdebug (port 10000)
cleanup                        remove all docker images
cs-fix                         run php-cs-fixer
day                            Retrieves the latest day's input from server
```
