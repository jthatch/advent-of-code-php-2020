<?php

declare(strict_types=1);

namespace App;

use App\Interfaces\DayInterface;

class Day1 extends DayBehaviour implements DayInterface
{
    /**
     * Find the two entries that sum to 2020; what do you get if you multiply them together?
     *
     * @return int|null
     */
    public function solvePart1(): ?int
    {
        $total = 2020;
        $list  = array_map('intval', $this->input);

        foreach ($list as $num1) {
            $num2 = $total - $num1;
            if ($total === $num1 + $num2 && in_array($num2, $list, true)) {
                return $num1 * $num2;
            }
        }

        return null;
    }

    /**
     * find the 3 numbers that sum to 2020.
     *
     * @return int|null
     */
    public function solvePart2(): ?int
    {
        $total = 2020;
        $list  = array_map('intval', $this->input);
        sort($list);
        // 3 numbers combined must be 2020, therefore any numbers > 2020 - 2 smallest combined = 1688
        // list goes from 200 to 89
        $biggestPossibleNum = $total - ($list[0] + $list[1]);
        $list               = array_filter($list, static fn ($i) => $i < $biggestPossibleNum);

        foreach ($list as $num1) {
            // subtract num1 from total, we are looking for 2 numbers that add up to that
            $numTarget = $total - $num1;
            foreach ($list as $num2) {
                if ($num2 === $num1) { // skip the same number
                    continue;
                }
                $num3     = $numTarget - $num2;
                $sumTotal = $num1 + $num2 + $num3;
                if ($total === $sumTotal && in_array($num3, $list, true)) {
                    return $num1 * $num2 * $num3;
                }
            }
        }

        return null;
    }
}
