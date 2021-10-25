<?php

declare(strict_types=1);

namespace App;

use App\Interfaces\DayInterface;

class Day14 extends DayBehaviour implements DayInterface
{
    protected const REGEX = [
        'mask' => '/mask = ([X10]{36})/is',
        'mem'  => '/mem\[(\d+)\] = (\d+)/is',
    ];

    public function solvePart1(): int|string|null
    {
        /*$this->input = explode("\n", <<<INPUT
        mask = XXXXXXXXXXXXXXXXXXXXXXXXXXXXX1XXXX0X
               1100110010010100111001110110
        mem[8] = 11
        mem[7] = 101
        mem[8] = 0
        INPUT);
        */
        $input = $this->input;
        $mem   = [];
        $mask  = [];
        foreach ($input as $i) {
            // check for mask first
            if (1 === preg_match(static::REGEX['mask'], $i, $matches)) {
                // split the mask into an array
                $mask = str_split($matches[1]);
                continue;
            }

            // a memory call
            if (1 === preg_match(static::REGEX['mem'], $i, $matches)) {
                [$pos, $val] = [(int) $matches[1], (int) $matches[2]];
                // convert value to binary and store in array
                $valBin = sprintf('%036b', $val);
                // now apply mask to $val
                foreach ($mask as $offset => $m) {
                    if ('X' === $m) {
                        continue;
                    }
                    $valBin[$offset] = $m;
                }
                // convert val back to integer and store
                $mem[$pos] = bindec($valBin);
            }
        }

        return (int) array_sum($mem);
    }

    public function solvePart2(): int|string|null
    {
        return null;
    }
}
