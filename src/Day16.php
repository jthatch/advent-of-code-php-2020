<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

namespace App;

use App\Interfaces\DayInterface;

class Day16 extends DayBehaviour implements DayInterface
{
    public function solvePart1(): ?int
    {
        [$rules,, $nearby] = $this->getTicketDataFromInput($this->input);
        $invalid           = [];
        foreach ($nearby as $ticketNumbers) {
            foreach ($ticketNumbers as $n) {
                $foundValidRule = null;
                foreach ($rules as [$a, $b]) {
                    $foundValidRule ??= ($n >= $a[0] && $n <= $a[1]) || ($n >= $b[0] && $n <= $b[1]) ? true : null;
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
        /**
         * @var array<string, array<int,array<int>>> $rules
         * @var array<int, array<int>>               $my
         * @var array<int, array<int>>               $nearby
         */
        [$rules, $my, $nearby] = $this->getTicketDataFromInput($this->input);

        // remove invalid tickets… these are tickets with numbers that don't pass any rules
        $nearby = array_filter(
            $nearby,
            fn (array $ticketNumbers) => $ticketNumbers === array_filter( // rule passing tickets will be unchanged
                $ticketNumbers,
                fn (int $n) => !empty(array_filter( // remove tickets that don't pass either rule condition
                    $rules,
                    fn (array $r) => ($n >= $r[0][0] && $n <= $r[0][1]) || ($n >= $r[1][0] && $n <= $r[1][1])
                ))
            )
        );

        // build a list of rule positions containing each rule that 100% applies to ticket numbers in that position
        // note: there will be multiple per position
        $maxScore = count($nearby);
        /* @var array<int,<string>> $rulePositions */
        $rulePositions = [];
        for ($position = 0, $positionMax = count($rules); $position < $positionMax; ++$position) {
            foreach ($rules as $ruleName => $ruleSet) {
                $numbersAtPosition = array_map(fn ($n) => $n[$position], $nearby);
                if ($maxScore === $this->getRuleScore($ruleSet, $numbersAtPosition)) {
                    $rulePositions[$position][] = $ruleName;
                }
            }
        }

        // We now have an array containing positions and every rule that applies to that position,
        // Many of the rules apply to multiple positions, so can't simply sort and pick the highest.
        // However, after sorting the positions by number of applicable rules, we discover there is a position
        // that has only a single rule, and the next position in turn has 2, one of which is our first rule,
        // and so on and so on… is your head hurting yet? :D
        //
        // With that in mind, we can get an ordered list containing one rule per position by doing the following:
        // 1. Sort ascending based on number of rules,
        // 2. Starting with the position with a single rule, assign that to a new array ($finalRulePositions)
        // 3. loop onward, removing rules from the current position that exist in $finalRulePositions
        // 4. We should be left with 1 rule per position.
        uasort($rulePositions, fn (array $a, array $b) => count($a) <=> count($b)); // sort ascending: no. of rules
        $finalRulePositions = [];
        foreach ($rulePositions as $position => $rulesInPosition) {
            $actualRules = array_diff($rulesInPosition, $finalRulePositions); // the delta will contain only 1 rule
            if (1 === count($actualRules)) {
                $finalRulePositions[$position] = array_pop($actualRules);
            }
        }

        // return the sum of all fields starting with departure from our ticket
        $departureRules = array_filter($finalRulePositions, fn (string $r) => str_starts_with($r, 'departure'));

        return array_reduce(array_keys($departureRules), fn (int $sum, int $key) => $sum * $my[0][$key], 1);
    }

    /**
     * Takes an array of rules and applies them to an array of ticket numbers, returning the valid score.
     *
     * @param array $rules
     * @param array $ticketNumbers
     *
     * @return int
     */
    protected function getRuleScore(array $rules, array $ticketNumbers): int
    {
        $score = 0;
        foreach ($ticketNumbers as $n) {
            $foundValidRule = null;
            foreach ($rules as [$min, $max]) {
                $foundValidRule ??= ($n >= $min && $n <= $max) ? true : null;
            }
            if ($foundValidRule) {
                ++$score;
            }
        }

        return $score;
    }

    /**
     * Extract ticket data from input, returning array containing `rules`, `my` and `nearby` tickets.
     *
     * @param array $input
     *
     * @return array
     */
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
        // loop over each line, when we encounter a blank new line, update our pointer ($p) to the next position
        foreach ($input as $line) {
            if (PHP_EOL === $line) {
                $p = array_shift($inputPositions);
            }
            $line = trim($line);

            // parse a rule e.g.: departure location: 29-458 or 484-956
            if (0 === $p) {
                [$ruleName, $numbers] = explode(':', $line, 2);
                if (1 <= preg_match_all('/(\d+)/', $numbers, $matches)) {
                    $ticket[$p][$ruleName] = array_map(fn (array $a) => array_map('intval', $a), array_chunk($matches[1], 2));
                }
            } elseif (str_contains($line, ',')) { // parse the comma separated list of numbers
                $ticket[$p][] = array_map('intval', explode(',', $line));
            }
        }

        return $ticket;
    }
}
