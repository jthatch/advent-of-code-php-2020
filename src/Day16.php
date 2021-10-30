<?php

declare(strict_types=1);

namespace App;

use App\Interfaces\DayInterface;

class Day16 extends DayBehaviour implements DayInterface
{
    public function solvePart1(): ?int
    {
        /*$this->input = array_map(static fn(string $l) => $l . "\n", explode("\n",<<<INPUT
        class: 1-3 or 5-7
        row: 6-11 or 33-44
        seat: 13-40 or 45-50

        your ticket:
        7,1,14

        nearby tickets:
        7,3,47
        40,4,50
        55,2,20
        38,6,12
        INPUT));*/

        [$rules,, $nearby] = $this->getTicketDataFromInput($this->input);

        $invalid = [];
        foreach ($nearby as $ticket) {
            foreach ($ticket as $n) {
                $foundValidRule = null;
                foreach ($rules as [$a, $b]) {
                    $foundValidRule ??= (($n >= $a[0] && $n <= $a[1]) || ($n >= $b[0] && $n <= $b[1])) ? true : null;
                }
                if (!$foundValidRule) {
                    $invalid[] = $n;
                }
            }
        }

        return (int) array_sum($invalid);
    }

    public function solvePart2(): ?int
    {
        // TODO: Implement solvePart2() method.
        return null;
    }

    protected function getTicketDataFromInput(array $input): array
    {
        $ticket = [
            [], // rules
            [], // my
            [], // nearby
        ];
        $inputPositions = array_keys($ticket);
        // start the pointer at rules
        $p = array_shift($inputPositions);
        // loop over each input, when we encounter a blank new line, update our pointer ($p) to the next position
        foreach ($input as $line) {
            if (PHP_EOL === $line) {
                $p = array_shift($inputPositions);
            }
            $line = trim($line);

            // parse a rule e.g.: departure location: 29-458 or 484-956
            if (0 === $p) {
                [$ruleName, $numbers] = explode(':', $line, 2);
                if (1 <= preg_match_all('/(\d+)/', $numbers, $matches)) {
                    $ticket[$p][$ruleName] = array_map(static fn (array $a) => array_map('intval', $a), array_chunk($matches[1], 2));
                }
            } elseif (str_contains($line, ',')) { // parse the comma separated list of numbers
                $nums = explode(',', $line);
                if (!empty($nums)) {
                    $ticket[$p][] = array_map('intval', $nums);
                }
            }
        }

        return $ticket;
    }
}
