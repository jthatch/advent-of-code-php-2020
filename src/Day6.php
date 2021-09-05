<?php

declare(strict_types=1);

namespace App;

use App\Interfaces\DayInterface;

class Day6 extends DayBehaviour implements DayInterface
{
    public function solvePart1(): ?int
    {
        // a blank newline represents a break, so batch up the groups
        $groups = [];
        $i      = 0;
        foreach ($this->input as $line) {
            if ("\n" === $line) {
                ++$i;
                continue;
            }
            $groups[$i] ??= [];
            $line       = trim($line); // remove newline
            $answers    = str_split($line);
            $groups[$i] = array_merge($groups[$i], $answers);
            $groups[$i] = array_unique($groups[$i]);
        }

        $sum = 0;
        foreach ($groups as $g) {
            $sum += count($g);
        }

        return $sum;
    }

    public function solvePart2(): ?int
    {
        // TODO: Implement solvePart2() method.
    }
}
