<?php

declare(strict_types=1);

namespace App;

use App\Contracts\DayBehaviour;

class Day6 extends DayBehaviour
{
    /**
     * For each group, count the number of questions to which anyone answered "yes". What is the sum of those counts?
     *
     * @return int|null
     */
    public function solvePart1(): ?int
    {
        $groups = [];
        $i      = 0;
        foreach ($this->input as $line) {
            if ("\n" === $line) {
                ++$i;
                continue;
            }
            $groups[$i] ??= [];
            $line       = trim($line); // remove newline
            $answers    = str_split($line);
            $groups[$i] = array_merge($groups[$i], $answers);
            $groups[$i] = array_unique($groups[$i]);
        }

        $sum = 0;
        foreach ($groups as $g) {
            $sum += count($g);
        }

        return $sum;
    }

    /**
     * For each group, count the number of questions to which everyone answered "yes". What is the sum of those counts?
     *
     * @return int|null
     */
    public function solvePart2(): ?int
    {
        $groups = [];
        $i      = 0;
        foreach ($this->input as $line) {
            if ("\n" === $line) {
                ++$i;
                continue;
            }
            $groups[$i] ??= [];
            $line    = trim($line); // remove newline
            $answers = str_split($line);
            // place each member's answers into an array within the group
            $groups[$i][] = $answers;
        }

        // we now have a multi-dimensional array $groups containing each individual's answers
        // the task is the find the answers that EVERY member of the group answered yes to
        $everyoneAnsweredYes = 0;
        foreach ($groups as $group) {
            $memberCount = count($group);
            $answers     = [];
            foreach ($group as $g) {
                /** @noinspection SlowArrayOperationsInLoopInspection */
                $answers = array_merge($answers, $g);
            }
            $everyoneAnswered = array_filter(
                array_count_values($answers),
                static fn (int $count, string $chr) => $count === $memberCount,
                ARRAY_FILTER_USE_BOTH
            );
            $everyoneAnsweredYes += count($everyoneAnswered);
        }

        return $everyoneAnsweredYes;
    }
}
