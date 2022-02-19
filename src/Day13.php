<?php

declare(strict_types=1);

namespace App;

use App\Contracts\DayBehaviour;

class Day13 extends DayBehaviour
{
    protected array $busses = [];

    /**
     * @param bool $withTimestamp
     *
     * @return array [offset => busId]
     */
    protected function getBussesFromInput(bool $withTimestamp = false): array
    {
        $timestamp = (int) trim($this->input[0]);
        $buses     = array_map(static fn (string $s) => 'x' === $s ? $s : (int) $s, explode(',', $this->input[1]));

        return $withTimestamp
            ? [$timestamp, $buses]
            : $buses;
    }

    public function solvePart1(): ?int
    {
        [$timestamp, $buses] = $this->getBussesFromInput(withTimestamp: true);
        $buses               = array_filter($buses, 'is_int');

        $earliestBuses = [];
        // loop over the busId's, populating a new array indexed by busId and the value
        // as the timestamp modulus busId subtracted from busId
        // this gives us the exact minute each bus will depart after the given timestamp
        array_walk($buses, static function (int $b) use (&$earliestBuses, $timestamp): void {
            $earliestBuses[$b] = $b - ($timestamp % $b);
        });
        // sort our array to find the earliest bus after timestamp
        asort($earliestBuses); // maintain indexes whilst sorting by value

        [$busId, $minutesAfterTs] = [array_key_first($earliestBuses), array_shift($earliestBuses)];

        return $busId * $minutesAfterTs;
    }

    /**
     * Lookup Chinese remainder Theorem.
     *
     * @return string|null
     */
    public function solvePart2(): ?string
    {
        // get only buses with departure time
        $buses   = array_filter($this->getBussesFromInput(), static fn ($b) => is_int($b));
        $product = array_product($buses);
        $sum     = 0;

        array_walk($buses, static function (int $bus, int $offset) use (&$sum, $product): void {
            $mod = (int) $product / $bus;
            if (false !== ($modInv = gmp_invert($mod, $bus))) {
                $offsetRemainder = $offset > 0 ? $bus - $offset : 0;
                $sum += $offsetRemainder * gmp_intval($modInv) * $mod;
            }
        });

        return (string) ($sum % $product);
    }
}
