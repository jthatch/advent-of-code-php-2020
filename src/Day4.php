<?php

declare(strict_types=1);

namespace App;

use App\Contracts\DayBehaviour;

class Day4 extends DayBehaviour
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
        return array_filter($passports, static fn ($p) => 8 === count($p) || (7 === count($p) && !isset($p['cid'])));
    }

    public function solvePart1(): ?int
    {
        return count($this->solvePart1ReturningPassports());
    }

    public function solvePart2(): ?int
    {
        $passports = $this->solvePart1ReturningPassports();

        return count(array_filter($passports, static function ($p) {
            return
                // byr (Birth Year) - four digits; at least 1920 and at most 2002.
                (1920 <= (int) $p['byr'] && 2002 >= (int) $p['byr'])
                // iyr (Issue Year) - four digits; at least 2010 and at most 2020.
                && (2010 <= (int) $p['iyr'] && 2020 >= (int) $p['iyr'])
                // eyr (Expiration Year) - four digits; at least 2020 and at most 2030.
                && (2020 <= (int) $p['eyr'] && 2030 >= (int) $p['eyr'])
                // hgt (Height) - a number followed by either cm or in:
                && (false !== (bool) preg_match('/(\d+)(cm|in)/', $p['hgt'], $matches)
                    && match ($matches[2]) {
                        // If cm, the number must be at least 150 and at most 193.
                        // If in, the number must be at least 59 and at most 76.
                        'cm'    => (150 <= (int) $matches[1] && 193 >= (int) $matches[1]),
                        'in'    => (59 <= (int) $matches[1] && 76 >= (int) $matches[1]),
                        default => false
                    })
                // hcl (Hair Color) - a # followed by exactly six characters 0-9 or a-f
                && (preg_match('/^#([a-f0-9]{3}){1,2}$/i', $p['hcl']))
                // ecl (Eye Color) - exactly one of: amb blu brn gry grn hzl oth.
                && (in_array($p['ecl'], ['amb', 'blu', 'brn', 'gry', 'grn', 'hzl', 'oth']))
                // pid (Passport ID) - a nine-digit number, including leading zeroes.
                && (preg_match('/^(\d{9})$/', $p['pid']));
        }));
    }
}
