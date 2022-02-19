<?php

declare(strict_types=1);

namespace App;

use App\Contracts\DayBehaviour;
use SplFixedArray;

class Day15 extends DayBehaviour
{
    /**
     * @see oldPart1() for original implementation. Very slow, very pants… no chance for part 2 unless bruteforcing it
     *
     * @return int
     */
    public function solvePart1(): int
    {
        return $this->numberSpoken(2020);
    }

    public function solvePart2(): int
    {
        return $this->numberSpoken(30_000_000);
    }

    /**
     * Part 2 requires us to calculate the 30 Millionth number spoken.
     * This rules out using a single array and calculating the turns by the index. Forget the memory, the scan operations
     * required to retrieve the last 2 turns would start taking forever, even in 20 years I doubt that would be practical!
     *
     * An alternative approach is as follows:
     * Create an array the size of the number we're after. SplFixedArray is ideal for this and makes lookups quicker
     * as the array is pre-initialised. The spoken number will never be larger than that.
     * Keep track of each numbers' last turn only, seeding our memory with the initial numbers incremented by 1,
     * then when we encounter a number we've seen before, we can subtract the current turn ($i) by the last seen
     * falling back to 0 if it doesn't exist.
     *
     * @param int $max
     *
     * @return int
     * @noinspection PhpRedundantVariableDocTypeInspection
     */
    protected function numberSpoken(int $max): int
    {
        $turns = array_map('intval', str_getcsv(trim($this->input[0])));

        /** @var SplFixedArray<int> $mem */
        $mem = new SplFixedArray($max);
        // $mem = []; // the tradeoff in less memory takes 75% longer
        $next = 0;
        $i    = 0;
        // add our existing turns to memory
        foreach ($turns as $t) {
            $mem[$t] = ++$i; // increment by 1 here so our $next calculation can be condensed to a single statement
            $next    = $t;
        }

        while ($i < $max) {
            $last       = $next;
            $next       = $i - ($mem[$next] ?? $i);
            $mem[$last] = $i++;
        }
        unset($mem);

        return $next;
    }

    /**
     * sloooooooooooow.
     *
     * @return int|string|null
     */
    public function oldPart1(): int|string|null
    {
        $turns = array_map('intval', str_getcsv(trim($this->input[0])));
        $max   = 2020;
        $i     = count($turns);
        while ($i < $max) {
            $last = $turns[count($turns) - 1];
            $seen = array_slice(array_filter($turns, static fn (int $no) => $no === $last, ARRAY_FILTER_USE_BOTH), -2, 2, true);
            if (1 === count($seen)) {
                $next = 0;
            } else {
                $last2 = array_map(static fn (int $i) => $i + 1, array_keys($seen));
                $next  = $last2[1] - $last2[0];
            }
            $turns[] = $next;
            ++$i;
        }

        return $turns[count($turns) - 1];
    }
}
