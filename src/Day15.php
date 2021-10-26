<?php

declare(strict_types=1);

namespace App;

use App\Interfaces\DayInterface;
use SplFixedArray;

class Day15 extends DayBehaviour implements DayInterface
{
    public function solvePart1(): int
    {
        return $this->numberSpoken(2020);
    }

    public function solvePart2(): int
    {
        // takes this long on my MBP
        // Mem: 32mb  Peak: 633.2mb Time: 11.10018s
        return $this->numberSpoken(30000000);
    }

    /**
     * Part 2 requires us to calculate the 30 Millionth number spoken.
     * This rules out using a single array and calculating the turns by the index. Forget the memory, the scan operations
     * required to retrieve the last 2 turns would start taking forever, even in 20 years I doubt that would be practical!
     *
     * Instead, whenever I encounter a number, I clone an instance of SplFixedArray fixed to 2 places.
     * This uses significantly less memory (2.8gb vs 663mb) than conventional arrays and is faster.
     * Some number shifting is required to keep track of the last 2 turns, but you'll see below this is quite optimised.
     *
     * @param int $max
     *
     * @return int
     */
    protected function numberSpoken(int $max): int
    {
        $turns = array_map('intval', str_getcsv(trim($this->input[0])));
        $i     = count($turns);

        // keep track of ever number we've heard spoken
        $mem = [];
        // our seen fifo queue. We only need a record of the last 2 positions a number was seen
        // SplFixedArray uses less memory and is faster than standard arrays
        $seenFifo = new SplFixedArray(2);

        $last = 0;
        // add our existing turns to the log
        foreach ($turns as $k => $t) {
            $mem[$t]    = clone $seenFifo;
            $mem[$t][0] = $k;
            $last       = $t;
        }

        // start the loop
        while ($i < $max) {
            // if we have seen the number before subtract the last two times from each other
            // otherwise return 0 if we haven't seen the number before
            $last = null !== $mem[$last][0] && null !== $mem[$last][1]
                ? $mem[$last][1] - $mem[$last][0]
                : 0;

            // if the number has been seen twice before, then shift the last becomes the first
            if (null !== ($mem[$last][1] ?? null)) { // seen twice
                $mem[$last][0] = $mem[$last][1];
                $mem[$last][1] = $i;
            } elseif (null !== ($mem[$last][0] ?? null)) { // seen once
                $mem[$last][1] = $i;
            } else { // first time, we'll need to clone our fifo queue for this one
                // cloning an object is significantly faster than constructing
                $mem[$last] ??= clone $seenFifo;
                $mem[$last][0] = $i;
            }

            ++$i;
        }

        return $last;
    }
}
