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
        // append my ticket
        $nearby = array_merge($nearby, $my);

        // now calculate the order the rules go
        $maxScore = count($nearby);
        /* @var array<int<string>> $rulePositions */
        $rulePositions = [];
        for ($position = 0, $positionMax = count($rules); $position < $positionMax; ++$position) {
            foreach ($rules as $ruleName => $ruleSet) {
                $numbersAtPosition = array_map(fn ($n) => $n[$position], $nearby);
                if ($maxScore === $this->getRuleScore($ruleSet, $numbersAtPosition)) {
                    $rulePositions[$position][] = $ruleName;
                }
            }
        }
        /* we now have an array containing positions and every rule that applies to that position
         many of the rules apply to multiple positions, so can't simply sort and pick the highest
         however after sorting the positions by number of applicable rules, we discover there is a position
         that has only a single rule, and the next position in turn has 2, one of which is our first rule,
         and so on and so on… is your head hurting yet? :D
         with all of that said, now we have an approach: starting with the position with a single rule,
         loop and remove assigned rules from the rest. This will give us ordered list containing one rule
         per position.*/
        uasort($rulePositions, fn ($a, $b) => count($a) <=> count($b));
        $finalRulePositions = [];
        foreach ($rulePositions as $position => $rulesInPosition) {
            $actualRules = array_diff($rulesInPosition, $finalRulePositions);
            if (1 === count($actualRules)) {
                $finalRulePositions[$position] = array_pop($actualRules);
            }
        }

        $departureRules = array_filter($finalRulePositions, fn (string $r) => str_starts_with($r, 'departure'));

        // returning the sum of all fields starting with departure from our ticket
        return array_reduce(array_keys($departureRules), fn ($sum, $key) => $sum * $my[0][$key], 1);
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
                $ticket[$p][] = array_map('intval', explode(',', $line));
            }
        }

        return $ticket;
    }
}
