<?php

declare(strict_types=1);

namespace App;

use App\Interfaces\DayInterface;

class Day7 extends DayBehaviour implements DayInterface
{
    protected array $bags = [];

    protected function getBags(): array
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

        return $bags;
    }

    /**
     * How many bag colors can eventually contain at least one shiny gold bag?
     *
     * @return int|null
     */
    public function solvePart1(): ?int
    {
        $bags = $this->getBags();

        $target = 'shiny gold';
        // 1st level, bags that directly contain target
        // 2nd level, bags that contain 1st level bags
        // 3rd level, bags that contain 2nd level bags
        // etc etc
        // we get the firstLevel bags and use that to seed our while loop
        $firstLevel = array_keys(array_filter($bags, static fn (array $innerBags, string $outerBag) => $innerBags[$target] ?? false, ARRAY_FILTER_USE_BOTH));

        $totalBags = $firstLevel;
        while (true) {
            $count = count($totalBags);
            foreach ($totalBags as $bag) {
                $totalBags = array_unique(array_merge($totalBags, array_keys(array_filter($bags, static fn (array $innerBags, string $outerBag) => $innerBags[$bag] ?? false, ARRAY_FILTER_USE_BOTH))));
            }
            if (count($totalBags) === $count) { // if we haven't found any new bags, exit
                break;
            }
        }

        return count($totalBags);
    }

    /**
     * Map the bags into a nested multi-dimensional tree structure of parent/children using recursion
     * this handles the `doubly logarithmic tree` problem where each bag contains multiple bags that contain multiple bags, ...and so on,.
     *
     * @param string $targetBag
     *
     * @return array|null
     */
    protected function bagTraverse(string $targetBag): ?array
    {
        $child = $this->bags[$targetBag] ?? [];

        return array_map(fn ($childBag, $count) => [
                'bag'   => $childBag,
                'count' => $count,
                'child' => $this->bagTraverse($childBag),
            ], array_keys($child), $child);
    }

    protected function bagCount(array $bagTree, $parentBagCount = 1): array
    {
        $bagCount = [];
        foreach ($bagTree as $tree) {
            $currCount  = $tree['count'] * $parentBagCount;
            $bagCount[] = $currCount;

            /** @noinspection SlowArrayOperationsInLoopInspection */
            $bagCount = array_merge($bagCount,
                ...array_map(fn ($childBags) => $this->bagCount([$childBags], $currCount), $tree['child']));
        }

        return $bagCount;
    }

    /**
     * How many individual bags are required inside your single shiny gold bag?
     * This requires us to implement and solve the "General Tree" data structure problem.
     *
     * @return int|null
     */
    public function solvePart2(): ?int
    {
        $this->bags = $this->getBags();
        $bagTree    = $this->bagTraverse('shiny gold');
        $bagCount   = $this->bagCount($bagTree);

        return array_sum($bagCount);
    }
}
