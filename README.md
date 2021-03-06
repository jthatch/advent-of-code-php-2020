## Advent of Code 2020 PHP
The solutions to [advent of code 2020](https://adventofcode.com/2020), solved using PHP 8.1. By [James Thatcher](http://github.com/jthatch)

### Solutions π₯³π
> π [Day 1](/src/Day1.php) π [Day 2](/src/Day2.php) βοΈ [Day 3](/src/Day3.php) π¦ [Day 4](/src/Day4.php) πͺ [Day 5](/src/Day5.php) 
> π₯ [Day 6](/src/Day6.php) π§¦ [Day 7](/src/Day7.php) π [Day 8](/src/Day8.php)   
> β [Day 9](/src/Day9.php) π [Day 10](/src/Day10.php) β [Day 11](/src/Day11.php) πͺ [Day 12](/src/Day12.php) βοΈ [Day 13](/src/Day13.php) π [Day 14](/src/Day14.php) π [Day 15](/src/Day15.php) π§¦ [Day 16](/src/Day16.php)
### About
My attempts at tacking the awesome challenges at [Advent of Code 2020](https://adventofcode.com/2020/day/1) using PHP 8.1.

For those who don't know what Advent of Code is, it is simply put, the **best** programming challenges you'll find on
the web.   
Every year, starting December 1st, you receive a new challenge. The challenges get harder and are humorous, involving
helping Santa and his elves deliver on their quest to deliver pressies on time.   

Each day comprises two parts, both based on the same input. You'll need to use all your smarts to solve these as they
rely on good knowledge of algorithms, logic and data structures. The true beauty of Advent of Code is the solutions 
can be written in any programming language.  

### π Best bits
**Best algorithm and optimisation: Day15**  
[Day15](/src/Day15.php), requires you to remember the numbers said in a list, appending the next based on when it was 
previously said.
My first attempt at this (part 1) was pants. I used a single array to keep track of every number spoken and used the 
array index to calculate turns. Fine for calculating the 2020th but when it came to the 30 millionthβ¦ No chance! π

After much playing around and refactoring, I was able to make some big improvements by using some lesser-known PHP 
class types like `SplFixedArray`. I've got this solution down to 1.3 seconds using 458mb of RAM. (_using a standard `array`
takes 3.7 seconds however it only uses 240mb of RAM_)
![day 15 part 2](/aoc-2020-jthatch-day15-pt2.png "AOC 2020 PHP Day 15 Part 2 in 1.3 secs")

### Tech
- Docker
- Makefile
- PHP8
- Pest  

As mentioned I've chosen PHP8 and I had a blast using the new language features.  

You'll find the solutions in the [/src](/src) directory; typically one file per Day, although [Day12](/src/Day12) was an exception, and I ended up abstracting this puzzle into objects.  

If you're feeling cheeky, the [/tests](/tests) folder
has your expected answers to each day. I use a [DayFactory](/src/DayFactory.php) to generate each day with the input in `$this->input` which frees me up to focus on the challenge.   

The runner [run.php](/run.php) detects which days have been completed and runs those, producing fancy output that times the execution time and memory consumption:

![runner output](/aoc-2020-jthatch-run-output.png "AOC 2020 PHP runner output")

The raw inputs are stored in [/input](/input), fetched automatically via the `make get-input` command.

Included in this repo is also a handy [Makefile](/Makefile) that launches a php docker container to execute the tests.

If you fancy having a go at the challenges yourself feel free to use this repo as a framework/skeleton.

### Commands
_Note: checkout the code then run `make run`. The docker and composer libraries will auto install._  

**Solve all days puzzles**  
`make run`

**Solve all days puzzles using the PEST testing framework**  
`make tests`

**Solve an individual days puzzles**  
`make run day={N}` e.g. `make run day=13`

**Solve a single part of a days puzzles**  
`make run day={N} part={N}` e.g. `make run day=16 part=2`

**Create the next days PHP files**  
_Auto detects what current Day you are on and will create the next (only if the files don't exist)_
```shell
make new
# Created new file: src/Day17.php
# Created new file: tests/Day17Test.php
```

**Fetch the next days input from the server.**  
`make get-input`  
_Note: The Makefile reads the [/src](/src) directory to find the most recent DayN.php file. If you had just completed `Day1.php` you would create a `Day2.php` (by running `make new`) and then run this command to fetch `/input/day2.txt`_

**Use XDebug**  
`make xdebug`  

**Xdebug can also be triggered on a single days and/or part**  
`make xdebug day={N}` e.g. `make xdebug day=13` or `make xdebug day=13 part=2`

IDE settings:
- `10000` - xdebug port 
- `aoc-2020` - PHP_IDE_CONFIG (what you put in PHPStorm -> settings -> debug -> server -> name)
- `/app` - absolute path on the server  
- see [xdebug.ini](/xdebug.ini) if you're stuck