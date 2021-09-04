<?php

declare(strict_types=1);

namespace App;

use App\Interfaces\DayInterface;

class Day4 extends DayBehaviour implements DayInterface
{
    protected function solvePart1ReturningPassports(): array
    {
        $passports = [];
        $i         = 0;
        foreach ($this->input as $line) {
            if ("\n" === $line) {
                ++$i;
                continue;
            }
            $passports[$i] ??= [];
            $line  = trim($line); // remove newline
            $parts = explode(' ', $line);
            foreach ($parts as $part) {
                [$key, $value]       = explode(':', $part);
                $passports[$i][$key] = $value;
            }
        }

        // valid passports: all 8 fields, or 7 except cid (santa has no country of origin)
        return array_filter($passports, static fn ($p) => 8 === count($p) || (7 === count($p) && !isset($p['cid']))
        );
    }

    public function solvePart1(): ?int
    {
        return count($this->solvePart1ReturningPassports());
    }

    public function solvePart2(): ?int
    {
        $passports = $this->solvePart1ReturningPassports();
        // todo finish this

        return null;
    }
}
