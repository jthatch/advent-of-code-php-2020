<?php

declare(strict_types=1);

namespace App;

use App\Contracts\DayBehaviour;

class Day14 extends DayBehaviour
{
    protected const REGEX = [
        'mask' => '/mask = ([X10]{36})/is',
        'mem'  => '/mem\[(\d+)\] = (\d+)/is',
    ];

    public function solvePart1(): int|string|null
    {
        $mem  = [];
        $mask = [];
        foreach ($this->input as $i) {
            // check for mask first
            if (1 === preg_match(static::REGEX['mask'], $i, $matches)) {
                // split the mask into an array
                $mask = str_split($matches[1]);
                continue;
            }

            // a memory call
            if (1 === preg_match(static::REGEX['mem'], $i, $matches)) {
                [$pos, $val] = [(int) $matches[1], (int) $matches[2]];
                // convert value to binary
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
        $mem  = [];
        $mask = [];
        foreach ($this->input as $i) {
            // check for mask first
            if (1 === preg_match(static::REGEX['mask'], $i, $matches)) {
                // split the mask into an array
                $mask = str_split($matches[1]);
                continue;
            }

            // a memory call
            if (1 === preg_match(static::REGEX['mem'], $i, $matches)) {
                [$pos, $val] = [(int) $matches[1], (int) $matches[2]];
                // convert position to binary
                $posMask = sprintf('%036b', $pos);
                // now apply mask to the position: 0 = unchanged, 1 = replace with 1, X = floating (see below)
                foreach ($mask as $offset => $m) {
                    if ('0' === $m) {
                        continue;
                    }
                    $posMask[$offset] = $m;
                }
                // now our memory position in binary ($posBin) contains a number of floating values (X)
                // these can be either 0 or 1 giving us 2^X possible memory combinations.
                // let's do this recursively
                $posCombos = $this->maskTraverse($posMask);
                foreach ($posCombos as $pos) {
                    $mem[bindec($pos)] = $val;
                }
            }
        }

        return (int) array_sum($mem);
    }

    /**
     * Recursively build up a list of all possible positions.
     * Example: given a $posBin like 000000000000000000000000000000X1101X with 2 X's, we get 4 combinations:
     *    000000000000000000000000000000(X)1101(X) becomes:
     * 1. 000000000000000000000000000000(0)1101(0)
     * 2. 000000000000000000000000000000(0)1101(1)
     * 3. 000000000000000000000000000000(1)1101(0)
     * 4. 000000000000000000000000000000(1)1101(1).
     *
     * @param string $posMask
     *
     * @return string[]
     */
    protected function maskTraverse(string $posMask): array
    {
        // get first occurrence of X in string
        if (false === ($x = strpos($posMask, 'X', 0))) {
            // if it doesn't exist that means we have a valid position, so return it as array
            return [$posMask];
        }

        // otherwise, recursively call ourselves twice, for 0 and 1
        return array_merge([], ...[
            $this->maskTraverse(substr_replace($posMask, '0', $x, 1)),
            $this->maskTraverse(substr_replace($posMask, '1', $x, 1)),
        ]);
    }
}
