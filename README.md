## Advent of Code 2020 PHP
The solutions to [advent of code 2020](https://adventofcode.com/2020), solved using PHP8. By [James Thatcher](http://github.com/jthatch)

### Solutions
> 🎄 [Day 1](/src/Day1.php) 🎅 [Day 2](/src/Day2.php) ☃️ [Day 3](/src/Day3.php) 🦌 [Day 4](/src/Day4.php) 🍪 [Day 5](/src/Day5.php) 
> 🥛 [Day 6](/src/Day6.php) 🧦 [Day 7](/src/Day7.php) 🎁 [Day 8](/src/Day8.php)   
> ⛄ [Day 9](/src/Day9.php) 🛐 [Day 10](/src/Day10.php)

### About
My attempts at tacking the awesome challenges at [Advent of Code 2020](https://adventofcode.com/2020/day/1) using PHP8.

For those who don't know what Advent of Code is, it is simply put, the **best** programming challenges you'll find on
the web.   
Every year, starting December 1st, you receive a new challenge. The challenges get harder and are humorous, involving
helping Santa and his elves deliver on their quest to deliver pressies on time.   

Each day comprises two parts, both based on the same input. You'll need to use all your smarts to solve these as they
rely on good knowledge of algorithms, logic and data structures. The true beauty of Advent of Code is the solutions 
can be written in any programming language.  

As mentioned I've chosen PHP8 and I had a blast using the new language features.  

You'll find the solutions in the [/src](/src) directory and if you're feeling cheeky, the [/tests](/tests) folder
has your expected answers to each day. The raw inputs are stored in [/input](/input), fetched automatically via the `make get-input` command.

Included in this repo is also a handy [Makefile](/Makefile) that launches a php docker container to execute the tests.

If you fancy having a go at the challenges yourself feel free to use this repo as a framework/skeleton.

### Commands

**Solve all days puzzles**  
`make run`

**Solve an individual days puzzles**  
`make run day={N}` e.g. `make run day=1`

**Use XDebug**  
`make xdebug` at the shell type: `vendor/bin/pest`  

IDE settings:
- `10000` - xdebug port 
- `aoc-2020` - PHP_IDE_CONFIG (what you put in PHPStorm -> settings -> debug -> server -> name)
- `/app` - absolute path on the server  
- see [xdebug.ini](/xdebug.ini) if you're stuck


- **Fetch the next days input from the server.**  
  `make get-input`  
  _Note: The Makefile reads the [/src](/src) directory to find the most recent DayN.php file. If you had just completed `Day1.php` you would create a `Day2.php` and then run this command to fetch `/input/day2.txt`_