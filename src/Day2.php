<?php

declare(strict_types=1);

namespace App;

use App\Interfaces\DayInterface;

class Day2 extends DayBehaviour implements DayInterface
{
    public function solvePart1(): ?int
    {
        return count(array_filter($this->input, static function ($line) {
            preg_match('/(\d+)-(\d+) (\w): (.+)/', $line, $matches);
            [,$min, $max, $chr, $password] = $matches;
            $chrOccurrences = substr_count($password, $chr);

            return (int) $min <= $chrOccurrences && (int) $max >= $chrOccurrences;
        }));
    }

    /**
     * Each policy actually describes two positions in the password...
     * Exactly one of these positions must contain the given letter.
     *
     * @return int|null
     */
    public function solvePart2(): ?int
    {
        return count(array_filter($this->input, static function ($line) {
            preg_match('/(\d+)-(\d+) (\w): (.+)/', $line, $matches);
            [,$position1, $position2, $chr, $password] = $matches;

            // padding string with a non-alpha character to fix index zero
            $passArray = str_split('#'.$password);

            return
                ($passArray[(int) $position1] === $chr && $passArray[(int) $position2] !== $chr) || // 1 matches, 2 doesn't
                ($passArray[(int) $position2] === $chr && $passArray[(int) $position1] !== $chr) // 2 matches, 1 doesn't
            ;
        }));
    }
}
