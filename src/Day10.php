<?php

declare(strict_types=1);

namespace App;

use App\Contracts\DayBehaviour;

class Day10 extends DayBehaviour
{
    protected array $cache = [];

    /**
     * Converts input to array of sorted integers then adds the initial starting joltage and the adapter max.
     *
     * @param array $inputArr
     *
     * @return array
     */
    protected function getInputWithMinMaxSorted(array $inputArr): array
    {
        $input = array_map(static fn (string $s): int => (int) trim($s), $inputArr);
        $input = array_merge([0], $input, [max($input) + 3]);
        sort($input);

        return $input;
    }

    /**
     * Find a chain that uses all of your adapters to connect the charging outlet to your device's built-in adapter
     * and count the joltage differences between the charging outlet, the adapters, and your device.
     * What is the number of 1-jolt differences multiplied by the number of 3-jolt differences?
     *
     * @return int|null
     */
    public function solvePart1(): ?int
    {
        $input = $this->getInputWithMinMaxSorted($this->input);
        // create a histogram based on the difference between each number
        $histogram = array_count_values(
            array_map(
                static fn (int $c, ?int $n) => ($n ?? $c) - $c, // find the delta between (c)urrent and (n)ext values in array
                $input,
                array_slice($input, 1)
            )
        );

        // return the number of 1-jolt x 3-jolts
        return $histogram[1] * $histogram[3];
    }

    protected function adapterTraverse(int $offset): int
    {
        if ($offset === count($this->input) - 1) {
            return 1;
        }

        $value = $this->input[$offset];
        $nodes = array_filter( // get a list of all adapters <= 3 jumps from $value
            array_slice($this->input, $offset + 1, $offset + 4, true),
            static fn (int $v) => ($v - $value) <= 3
        );
        // cache the offset, only way this completes in time
        $this->cache[$offset] ??= array_sum(array_map(fn (int $k) => $this->adapterTraverse($k), array_keys($nodes)));

        return $this->cache[$offset];
    }

    /**
     * What is the total number of distinct ways you can arrange the adapters to connect the charging outlet to your device?
     *
     * @return int|null
     */
    public function solvePart2(): ?int
    {
        // the test examples
        //$this->input = ['16', '10', '15', '5', '1', '11', '7', '19', '6', '12', '4'];
        //$this->input = ['28', '33', '18', '42', '31', '14', '46', '20', '48', '47', '24', '23', '49', '45', '19', '38', '39', '11', '1', '32', '25', '35', '8', '17', '7', '9', '4', '2', '34', '10', '3'];
        $this->input = $this->getInputWithMinMaxSorted($this->input);

        return $this->adapterTraverse(0);
    }
}
