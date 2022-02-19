<?php

declare(strict_types=1);

namespace App;

use App\Contracts\DayBehaviour;

class Day9 extends DayBehaviour
{
    /**
     * The first step of attacking the weakness in the XMAS data is to find the first number in the list (after the preamble)
     * which is not the sum of two of the 25 numbers before it. What is the first number that does not have this property?
     *
     * @return int|null
     */
    public function solvePart1(): ?int
    {
        // convert input to array of integers
        $input    = array_map(static fn (string $s): int => (int) trim($s), $this->input);
        $inputLen = count($input);

        // skipping the preamble (we'll retrieve the last 5 inside the loop) loop over every number
        for ($i = 25; $i < $inputLen; ++$i) {
            $currentNum = $input[$i];
            $last5      = array_filter($input, static function (int $k) use ($i): bool {
                return $k < $i && ($i - 25) <= $k;
            }, ARRAY_FILTER_USE_KEY);

            // find 2 numbers in the $last5 that sum to $n and aren't equal
            // we can reuse code from Day 1
            $sum = array_filter($last5, static function (int $num1) use ($currentNum, $last5): bool {
                $num2 = $currentNum - $num1;

                return $num1 !== $num2 && $currentNum === $num1 + $num2 && in_array($num2, $last5, true);
            });

            if (empty($sum)) {
                return $currentNum;
            }
        }

        return null;
    }

    /**
     * Find a contiguous set of numbers that add up to 2089807806, then sum the smallest and largest to find the encryption weakness.
     * What is the encryption weakness in your XMAS-encrypted list of numbers?
     *
     * @return int|null
     */
    public function solvePart2(): ?int
    {
        // convert input to array of integers
        $input = array_map(static fn (string $s): int => (int) trim($s), $this->input);

        // retrieved from part1
        $target = 2089807806;

        // now we need to find a contiguous set of numbers that add up to $target
        // foreach number in the list, read the next number(s) to see if they add up to $target
        foreach ($input as $index => $startNum) {
            $nextInput     = array_slice($input, $index + 1);
            $contiguousSet = [$startNum];
            foreach ($nextInput as $currentNum) {
                $contiguousSet[] = $currentNum;
                $total           = array_sum($contiguousSet);
                if ($total === $target) {
                    sort($contiguousSet);

                    return $contiguousSet[0] + $contiguousSet[count($contiguousSet) - 1];
                }
            }
        }

        return null;
    }
}
