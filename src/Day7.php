<?php

declare(strict_types=1);

namespace App;

use App\Interfaces\DayInterface;

class Day7 extends DayBehaviour implements DayInterface
{
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
                /** @noinspection SlowArrayOperationsInLoopInspection */
                $totalBags = array_unique(array_merge($totalBags, array_keys(array_filter($bags, static fn (array $innerBags, string $outerBag) => $innerBags[$bag] ?? false, ARRAY_FILTER_USE_BOTH))));
            }
            if (count($totalBags) === $count) { // if we haven't found any new bags, exit
                break;
            }
        }

        return count($totalBags);
    }

    /**
     * How many individual bags are required inside your single shiny gold bag?
     * @return int|null
     */
    public function solvePart2(): ?int
    {
        // 126 = 2 + 4 + 8 + 16 + 32 + 64
       /* $this->input = ["shiny gold bags contain 2 dark red bags.", // 2
            "dark red bags contain 2 dark orange bags.", // 4
            "dark orange bags contain 2 dark yellow bags.", // 8
            "dark yellow bags contain 2 dark green bags.", // 16
            "dark green bags contain 2 dark blue bags.", // 32
            "dark blue bags contain 2 dark violet bags.", // 64
            "dark violet bags contain no other bags.",];*/ // 0

        // 60 (1 * 10 * 2 * 2) + ( 1 * 5 * 2 * 2)
        /*$this->input = ["shiny gold bags contain 10 dark red bags, 5 dark yellow bags.",
            "dark red bags contain 2 dark orange bags.",
            "dark yellow bags contain 2 dark green bags.",
            "dark orange bags contain no other bags.",
            "dark green bags contain no other bags.",
            ];*/
        // 100 = 1 * 10 * 5 * 2
        $this->input = ["shiny gold bags contain 10 dark red bags.",
            "dark red bags contain 5 dark orange bags.",
            "dark orange bags contain no other bags.",
        ];

        $bags = $this->getBags();

        // same approach as part 1 except we need to count the values
        $targets = ['shiny gold'];
        $bagCounts = [];
        $individualBagCount = 0;
        while(true) {
            if (null === ($target = array_pop($targets))) {
                break;
            }
            $possibleBags =
            // with target, we need to traverse every possible bag it contains
            // shiny gold -> dark red -> dark orange


        }
        while(($target = array_pop($targets)) && !empty($bags[$target])) {
            // with target, we need to traverse every possible bag it contains
            // shiny gold -> dark red -> dark orange
            $bagCounts[] = array_values($bags[$target])[0];
            //$individualBagCount += array_values($bagsarray_sum($bags[$target]);
            $targets = array_keys($bags[$target]);
            print_r($targets);
            /*$bagStack =
                array_filter($bags, static function (array $innerBags, string $outerBag) use ($target) {
                    print_r($innerBags);
                    echo "outerBag: $outerBag";
                    return in_array($outerBag, $target, true);
                }, ARRAY_FILTER_USE_BOTH);
            $individualBagCount = array_reduce($bagStack, function($count, $bag) {
                $count += array_count_values($bag);
                return $count;
            }, $individualBagCount);*/

        }

        return array_product($bagCounts);
        $firstLevel = $bags['shiny gold'];

        $totalBags = $firstLevel;
        $individualBagCount = 0;
        while (true) {
            $count = count($totalBags);
            foreach ($totalBags as $bag => $count) {
                $individualBagCount+=$count;
                /** @noinspection SlowArrayOperationsInLoopInspection */
                $newBags = array_filter($bags, static fn (array $innerBags, string $outerBag) => $innerBags[$bag] ?? false, ARRAY_FILTER_USE_BOTH);
                $individualBagCount+= array_sum(array_values($newBags));
                $totalBags = array_unique(array_merge($totalBags,...$newBags));
            }
            if (count($totalBags) === $count) { // if we haven't found any new bags, exit
                break;
            }
        }

        return count($totalBags);
    }
}
