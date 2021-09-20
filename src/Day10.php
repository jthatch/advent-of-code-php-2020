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
     *  - we always want to use the device with the lowest difference between joltage.
     *
     * @return int|null
     */
    public function solvePart1(): ?int
    {
        $input = $this->inputAsInt();
        sort($input);
        $oneJoltDiff   = [];
        $threeJoltDiff = [];
        $joltage       = 0;
        foreach ($input as $deviceJoltage) {
            if ($deviceJoltage === ($joltage + 1)) {
                $oneJoltDiff[] = $deviceJoltage;
            } elseif ($deviceJoltage === ($joltage + 3)) {
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
        /*$this->input = [
                    '16',
                    '10',
                    '15',
                    '5',
                    '1',
                    '11',
                    '7',
                    '19',
                    '6',
                    '12',
                    '4',
                ];*/
        /*while(true) {
            $possibleDevices = array_filter($input, static fn(int $i, int $k) => $i < ($joltage + 3), ARRAY_FILTER_USE_BOTH);
            if (1 === count($possibleDevices)) {
                // now set
            }
            foreach($possibleDevices as $deviceJoltage) {

            }


        }*/

        // rules, given an input, it can go 1-3 volts lower, and 3 volts higher
        // e.g. input 5: valid: 2,3,4,8
        return null;
    }
}
