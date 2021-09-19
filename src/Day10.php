<?php

declare(strict_types=1);

namespace App;

use App\Interfaces\DayInterface;

class Day10 extends DayBehaviour implements DayInterface
{
    /**
     * Find a chain that uses all of your adapters to connect the charging outlet to your device's built-in adapter
     * and count the joltage differences between the charging outlet, the adapters, and your device.
     * What is the number of 1-jolt differences multiplied by the number of 3-jolt differences?
     * rules:
     *  - need to use every device
     *  - we always want to use the device with the lowest difference between joltage
     * @return int|null
     */
    public function solvePart1(): ?int
    {
        $input = $this->inputAsInt();
        sort($input);
        $oneJoltDiff = [];
        $threeJoltDiff = [];
        $joltage = 0;
        foreach($input as $deviceJoltage) {
            if ($deviceJoltage === ($joltage + 1)) {
                $oneJoltDiff[] = $deviceJoltage;
            } else if ($deviceJoltage === ($joltage + 3)) {
                $threeJoltDiff[] = $deviceJoltage;
            }
            $joltage = $deviceJoltage;
        }
        // add final 3 jolt
        $threeJoltDiff[] = max($input) + 3;

        return count($oneJoltDiff) * count($threeJoltDiff);
    }

    /**
     * @return int|null
     */
    public function solvePart2(): ?int
    {

        return null;
    }
}
