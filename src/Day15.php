<?php

declare(strict_types=1);

namespace App;

use App\Interfaces\DayInterface;

class Day15 extends DayBehaviour implements DayInterface
{
    public function solvePart1(): int|string|null
    {
        //$this->input[0] = '0,3,6';
        //$this->input[0] = '3,1,2';
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
        // TODO: Implement solvePart2() method.
        return null;
    }
}
