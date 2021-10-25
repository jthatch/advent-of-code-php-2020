<?php

declare(strict_types=1);

namespace App;

use App\Interfaces\DayInterface;

class Day15 extends DayBehaviour implements DayInterface
{
    public function solvePart1(): int|string|null
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

    public function solvePart2(): int|string|null
    {
        // this takes 12s and uses 1.42gb of memory!
        //return 1065;
        $turns = array_map('intval', str_getcsv(trim($this->input[0])));
        $max   = 30000000;
        $i     = count($turns);

        // <no => [turn2,turn1]>
        $mem  = [];
        $last = 0;
        // seed with starting numbers, turn always starts from 1
        foreach ($turns as $k => $t) {
            $mem[$t] = [$k + 1];
            $last    = $t;
        }

        while ($i < $max) {
            // loop backwards from end of array
            if (1 === count($mem[$last])) {
                $next = 0;
            } else {
                $next       = $mem[$last][1] - $mem[$last][0];
                $mem[$last] = array_slice($mem[$last], -2);
            }
            $mem[$next][] = $i + 1;
            $mem[$next]   = array_slice($mem[$next], -2);
            $last         = $next;
            ++$i;
        }

        return $last;
    }
}
