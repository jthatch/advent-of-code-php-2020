## Advent of Code PHP 2020 
>The solutions to advent of code 2020 in php, specifically PHP8/Jest, by [James Thatcher](http://github.com/jthatch)

### About

As mentioned this is my attempt at tacking the awesome challenges at [Advent of Code 2020](https://adventofcode.com/2020/day/1) using PHP8.

You will find the solutions in the [/src](/src) directory and if you're feeling cheeky, the [/tests](/tests) folder
has your expected inputs. The raw inputs are stored in [/input](/input), fetched automatically via a cheeky `make day` command.

Included in this repo is also a handy [Makefile](/Makefile) that launches a php docker container to execute the tests.

If you fancy having a go at the challenges yourself feel free to use this repo as a framework/skeleton.

It has many helpful features to make tackling each Days' challenge as easy as possible, as well as commands like `make day` 
that will automatically fetch the input from [adventofcode](https://adventofcode.com)'s servers.

### Instructions
- **Run all tests**  
 `make tests`  
  
  
- **Fetch the next days input from the server.**  
  _Note: The Makefile reads the [/src](/src) directory to find the most recent DayN.php file. If you had just completed `Day1.php` you would create a `Day2.php` and then run this command to fetch `/input/day2.txt`_  
  `make day`

### Full make commands
```shell
help                           This help.
tests                          runs the pest tests for each Day's answer
composer                       Runs `composer update` on CWD, specify other commands via cmd=
shell                          Launch a shell into the docker container
xdebug                         Launch a php container with xdebug (port 10000)
xdebug-down                    stop xdebug
down                           stop's the php docker container
cleanup                        remove all docker images
day                            Retrieves the latest day's input from server
```