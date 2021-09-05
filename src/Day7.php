<?php

declare(strict_types=1);

namespace App;

use App\Interfaces\DayInterface;

class Day7 extends DayBehaviour implements DayInterface
{
    public function solvePart1(): ?int
    {
        // 2d array in the format $bag[outerBag][innerBags[]] = count
        // something we can traverse by key and loop a 2nd time to find all potential bags
        $bags = [];
        foreach ($this->input as $rule) {
            [$colour, $contains] = array_map('trim', explode('contain', $rule, 2));
            $colour              = str_replace(' bags', '', $colour);
            $bags[$colour] ??= [];
            preg_match_all('/(\d) ([a-z ]+) bags?/', $contains, $matches, PREG_SET_ORDER);
            $bags[$colour] = array_merge($bags[$colour], ...array_map(static fn (array $m) => [$m[2] => (int) $m[1]], $matches));
        }

        $target = 'shiny gold';
        // 1st level, bags that directly contain target
        // 2nd level, bags that contain 1st level bags
        // 3rd level, bags that contain 2nd level bags
        // etc etc
        // we get the firstLevel bags and use that to seed our while loop
        $firstLevel = array_keys(array_filter($bags, fn (array $innerBags, string $outerBag) => $innerBags[$target] ?? false, ARRAY_FILTER_USE_BOTH));

        $totalBags = $firstLevel;
        while (true) {
            $count = count($totalBags);
            foreach ($totalBags as $bag) {
                /** @noinspection SlowArrayOperationsInLoopInspection */
                $totalBags = array_unique(array_merge($totalBags, array_keys(array_filter($bags, static fn (array $innerBags, string $outerBag) => $innerBags[$bag] ?? false, ARRAY_FILTER_USE_BOTH))));
            }
            if (count($totalBags) === $count) { // if we haven't found any new bags, exit
                break;
            }
        }

        return count($totalBags);
    }

    public function solvePart2(): ?int
    {
        // TODO: Implement solvePart2() method.

        return null;
    }
}
