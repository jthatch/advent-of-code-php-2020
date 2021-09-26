<?php

declare(strict_types=1);

namespace App;

use App\Interfaces\DayInterface;

class Day10 extends DayBehaviour implements DayInterface
{
    protected int $loop      = 0;
    protected array $offsets = [];

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
                $input, array_slice($input, 1)));

        // return the number of 1-jolt x 3-jolts
        return $histogram[1] * $histogram[3];
    }

    public function arrangementsFromOffset(int $offset): int
    {
        $sum         = 0;
        $sums        = [];
        $inputTotal  = count($this->input);
        $offsetInput = $this->input[$offset];
        if ($offset >= $inputTotal) {
            return 1;
        }

        //for (; $offset < $inputTotal; ++$offset) {
        $range = range($offset + 1, min($inputTotal - 1, $offset + 4));
        /** @noinspection SlowArrayOperationsInLoopInspection */
        $sums = array_merge($sums, ...array_map(function (int $j) use ($offset) {
            $offsetInput = $this->input[$offset];
            $jInput = $this->input[$j] ?? 0;
            if (($jInput - $offsetInput) <= 3) {
                return [$this->arrangementsFromOffset($j)];
            }

            return [];
        }, $range));

        $sum += array_sum($sums);
        //}

        return $sum;
        /*foreach(range($offset + 1, min(count($this->input), $offset + 4)) as $j) {
            if (3 <= ($this->input[$j] - $this->input[$offset])) {
                return $this->arrangementsFromOffset($j);
            }
        }*/
    }

    protected function adapterTraverse(int $offset): int
    {
        ++$this->loop;
        if (0 === $this->loop % 10000000) {
            echo "loop: {$this->loop}\n";
        }
        //$this->offsets[] = $offset;
        if ($offset === count($this->input) - 1) {
            return 1;
        }

        $value = $this->input[$offset];
        $nodes = array_filter( // get a list of all adapters <= 3 jumps from $value
            array_slice($this->input, $offset + 1, $offset + 4, true),
            static fn (int $v) => ($v - $value) <= 3);
        /*if (array_key_exists(102, $nodes)) {
            return 1;
        }*/
        //file_put_contents('log.log', file_get_contents('log.log') .  sprintf("offset: %d value: %d nodes: %s\n", $offset, $value, json_encode($nodes)));
        $sum = array_sum(array_map(fn (int $k) => $this->adapterTraverse($k), array_keys($nodes)));

        return $sum;
        //file_put_contents('log.log', file_get_contents('log.log') .  sprintf("offset: %d value: %d sum: %d nodes: %s\n", $offset, $value, $sum, json_encode($nodes)));
    }

    /**
     * What is the total number of distinct ways you can arrange the adapters to connect the charging outlet to your device?
     *
     * @return int|null
     */
    public function solvePart2(): ?int
    {
        //       3  2          2
        //       x  x          x
        /*
                  [5, 6]       [11]
        (0), 1, 4, 5, 6, 7, 10, 11, 12, 15, 16, 19, (22) 13 hops max 10 hops min = 3 diff
        (0), 1, 4, 5, 6, 7, 10, 12, 15, 16, 19, (22)      11
        (0), 1, 4, 5, 7, 10, 11, 12, 15, 16, 19, (22)     6
        (0), 1, 4, 6, 7, 10, 11, 12, 15, 16, 19, (22)     5
        (0), 1, 4, 5, 7, 10, 12, 15, 16, 19, (22)         6,11
        (0), 1, 4, 6, 7, 10, 12, 15, 16, 19, (22)         5,11
        (0), 1, 4, 7, 10, 11, 12, 15, 16, 19, (22)        5,6
        (0), 1, 4, 7, 10, 12, 15, 16, 19, (22)            5,6,11
         */
        // 1, 4, 5, 6, 7, 10, 11, 12, 15, 16, 19 (11)
        // 1, 4, 5, 6, 7, 10, 12, 15, 16, 19 (11)
        // 1, 4, 5, 7, 10, 12, 15, 16, 19 (11)
        // 1, 4, 7, 10, 12, 15, 16, 19           (8)
        //$this->input = ['16', '10', '15', '5', '1', '11', '7', '19', '6', '12', '4'];
        //$this->input = ['28', '33', '18', '42', '31', '14', '46', '20', '48', '47', '24', '23', '49', '45', '19', '38', '39', '11', '1', '32', '25', '35', '8', '17', '7', '9', '4', '2', '34', '10', '3'];
        //$this->input = ['1', '4', '5', '6', '7'];
        // assign it to our object variable, so we can traverse it
        $this->input = $this->getInputWithMinMaxSorted($this->input);

        $total = $this->adapterTraverse(0);

        return $total;

        return $this->arrangementsFromOffset(0);
        // todo start at end -1, calculate how many paths there are and continue down
        $joltage  = 0;
        $distinct = [];
        $max      = $input[count($input) - 1];
        $count    = 0;
        $disCount = 1;
        $shortest = [];
        $longest  = [];
        while (true) {
            $possibleDevices = array_filter($input, static fn (int $i) => $i > $joltage && $i <= ($joltage + 3));
            // take the highest possible joltage
            if (!empty($possibleDevices)) {
                $longest = array_merge($longest, $possibleDevices);
            }
            $joltage    = array_pop($possibleDevices);
            $shortest[] = $joltage;
            // add any left over to our array
            if (!empty($possibleDevices)) {
                /** @noinspection SlowArrayOperationsInLoopInspection */
                $distinct = array_merge($distinct, $possibleDevices ?? []);

                //$joltage = array_pop($possibleDevices);
                ++$disCount;
            }
            /*if (!empty($possibleDevices)) {
                // @noinspection SlowArrayOperationsInLoopInspection
                $distinct = array_merge($distinct, [$possibleDevices]);
            }*/
            ++$count;
            if ($joltage >= $max) {
                break;
            }
        }
        // find the power set
        //$ans = count($pc_array_power_set($distinct));
        //echo implode(',', $distinct) . "\n";
        //return null;
        //$ans = 2 ** count($distinct);
        //return $ans;
        $ans = 2 ** 3 * $disCount ** (count($distinct) - $disCount);
        //$ans = 2 ** count($distinct);
        // 549755813888 too low
        // 68719476736 too low
        //return $ans;
        return null;
    }
}
