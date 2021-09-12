<?php

declare(strict_types=1);

namespace App;

use App\Interfaces\DayInterface;

class Day7 extends DayBehaviour implements DayInterface
{
    protected array $bags = [];
    protected array $tree = [];

    protected function getBags(): array
    {
        // 2d array in the format $bag[outerBag][innerBags[]] = count
        // something we can traverse by key and loop a 2nd time to find all potential bags
        $bags = [];
        foreach ($this->input as $rule) {
            [$colour, $contains] = array_map('trim', explode('contain', $rule, 2));
            $colour              = str_replace(' bags', '', $colour);
            $bags[$colour]     ??= [];
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
                $totalBags = array_unique(array_merge($totalBags, array_keys(array_filter($bags, static fn (array $innerBags, string $outerBag) => $innerBags[$bag] ?? false, ARRAY_FILTER_USE_BOTH))));
            }
            if (count($totalBags) === $count) { // if we haven't found any new bags, exit
                break;
            }
        }

        return count($totalBags);
    }

    protected function traverseList($nodes, int $previous = 1): \Iterator {
        $bagCounts = [];
        foreach($nodes as $bagName => $bagCount) {
            //yield $bagName => $bagCount;
            $bagCounts[] = $bagCount;
            //$bagCounts[] = $bagCount * ($bagCounts[count($bagCounts) - 1] ?? 1); // multiply the previous count with current and add that
            if ($childBags = ($this->bags[$bagName] ?? null)) {
                if (!empty($childBags)) {
                    $child = $this->traverseList($childBags, $bagCount);
                    //$count = $child->current() * ($bagCounts[count($bagCounts) - 1] ?? 1);
                    $count = $child->current() * $previous;
                    $bagCounts[] = $count; // multiply the previous count with current and add that
                    yield $child->key() => $count;
                    //continue;
                } else {
                    continue;
                }
                //yield $count;
                //continue;
            }
            yield $bagName => ($bagCount * $previous);
            //yield $bagCount;
            //yield $bagName => $bagCount;
        }
        //yield array_sum($bagCounts);
    }

    protected function traverseChild($nodes, bool $root = false): \Generator
    {
        if ($root) {
            $bagCounts ??= [];
            $bagTree ??= [];
        }
        foreach($nodes as $bagName => $count) {
            if (!$root) {
                yield $bagName => $count; // just return the first
                continue;
            } else { // traverse
                $target = $bagName;
                $childBags = ($this->bags[$target] ?? null);
                $bagTree[$bagName] = array_fill_keys(array_keys($childBags), []);
                $pointer = &$bagTree[$bagName];
                $loop = true;
                while($loop) {
                    foreach(array_keys($pointer) as $key) {
                        $tempBags = array_keys($this->bags[$key]);
                        $pointer[$key] = $tempBags;
                    }
                    $loop = false;
                }
                $bagCounts[] = $count;
                if (empty($childBags)) { // we reached the end of this tree
                    echo "reached end of $bagname with $target";
                    continue;
                } else {
                //while(($childBags = ($this->bags[$target] ?? null)) && !empty($childBags)) {
                    foreach($this->traverseChild($childBags) as $childName => $childCount) {
                        $count = $childCount * ($bagCounts[count($bagCounts) - 1] ?? 1);
                        $bagCounts[] = $count;
                        $target = $childName;
                    }
                    yield $bagName => array_sum($bagCounts);
                }
            }
            // at this stage we have a bag name and count
            // we need to enter a traversal, storing what we need as we go
            /*while(($childBags = ($this->bags[$bagName] ?? null)) && !empty($childBags)) {
                yield from $this->traverseChild($childBags, $count);
                $count = $child->current() * ($bagCounts[count($bagCounts) - 1] ?? 1);
            }*/
            /*if ($childBags = ($this->bags[$bagName] ?? null)) {
                $this->traverseChild($childBags, $bagCount);
                continue;
            }*/
        }
    }

    protected function buildTree(string $target, array $items): array
    {
        $tree = [];
        $tree[$target] = array_column(array_map(function($item, $count) {
            return ['child' => $item,'count' => $count];
        }, array_keys($items[$target]), $items[$target]), 'child', 'count');

        $root = $items[$target];

        //return [$bag => $items];
        return [];
    }

    /**
     * Map the bags into a nested multi-dimensional tree structure of parent/children using recursion
     * this handles the `doubly logarithmic tree` problem where each bag contains multiple bags that contain multiple bags, ...and so on,
     *
     * @param string $targetBag
     * @return array|null
     */
    protected function bagTraverse(string $targetBag): ?array
    {
        $child = $this->bags[$targetBag] ?? [];

        $data = array_map(fn ($childBag, $count) => [
                'bag'   => $childBag,
                'count' => $count,
                'child' => $this->bagTraverse($childBag)
            ], array_keys($child), $child);

        return $data;
    }

    protected function bagCount(array $bagTree, $parentBagCount = 1): array
    {
        $bagCount = [];
        foreach($bagTree as $tree) {
            $currCount = $tree['count'] * $parentBagCount;
            $bagCount[] = $currCount;

            /** @noinspection SlowArrayOperationsInLoopInspection */
            $bagCount = array_merge($bagCount,
                ...array_map(function ($childBags) use ($currCount) {
                return $this->bagCount([$childBags], $currCount);
            }, $tree['child']));
        }
        return $bagCount;
    }

    /**
     * How many individual bags are required inside your single shiny gold bag?
     * This requires us to implement and solve the "General Tree" data structure problem
     * @return int|null
     */
    public function solvePart2(): ?int
    {
        $this->bags = $this->getBags();
        $bagTree = $this->bagTraverse('shiny gold');
        $bagCount = $this->bagCount($bagTree);

        return array_sum($bagCount);

        /*array_walk($tree, function(&$node, $index, $foo) {
            $node['child'] = $this->treeTraverse($node);

        }, $tree);*/
        $rootNode = $bags['shiny gold'];
        $target = 'shiny gold';

        $tree = array_map(function($item, $count) use ($bags) {
            return ['child' => [$item => $this->treeTraverse($bags[$item],$item)],'count' => $count];
        }, array_keys($bags[$target]), $bags[$target]);
        $keys = array_keys($bags);

        //$tree = array_map(function($item, $key))
        /*$tree = static::groupByFnRecursive($bags, [
            static function (array $item) {
                return key($item);
            },
        ]);*/
        $this->tree = $tree;

        $rootNode = $bags['shiny gold'];
        $bagList = ['shiny gold' => []];
        $bagPointer = &$bagList['shiny gold'];
        $bagCounts = [];
        foreach($this->traverseChild($rootNode, true) as $node => $count) {
            $bagCounts[] = $count;
        }
        return array_sum($bagCounts);
        /*foreach($rootNode as $firstLevel => $count) {
            $bagCounts[] = $count;
            if ($childBags = ($this->bags[$firstLevel] ?? null)) {
                foreach($this->traverseChild($childBags) as $child => $childCount) {
                    $bagCounts[] = $childCount;
                }
            }
        }*/

        foreach($this->traverseList($rootNode) as $node => $count) {
            $bagPointer[$node] ??= [];
            echo $node . " " . $count;
            $bagPointer = &$bagPointer[$node];
            $bagCounts[] = $count;
            print_r($bagCounts);
            //$bagCounts[] = $count * ($bagCounts[count($bagCounts) - 1] ?? 1); // multiply the previous count with current and add that
        }
        return array_sum($bagCounts);
        // starting with the same bag array as part 1 except now we need to count the combined number of bags our shiny gold bag has
        // this becomes a `doubly logarithmic tree` problem as each bag contains multiple bags that contain multiple bags, ...and so on,
        // from our starting point, we get the first $nextBag(s) -- the bags our shiny gold bag contains
        // from there we can traverse that bags tree until no more bags are found, accumulating a count
        // we repeat this for all $nextBag(s)
        // traversal, node, children

        // 60 (1 * 10 * 2 * 2) + ( 1 * 5 * 2 * 2)
        /*$this->input = ["shiny gold bags contain 10 dark red bags, 5 dark yellow bags.",
            "dark red bags contain 2 dark orange bags.",
            "dark yellow bags contain 2 dark green bags.",
            "dark orange bags contain no other bags.",
            "dark green bags contain no other bags.",
            ];*/
        // 100 = 1 * 8 * 6
        /*$this->input = ["shiny gold bags contain 8 dark red bags.",
            "dark red bags contain 6 dark orange bags.",
            "dark orange bags contain no other bags.",
        ];*/

        // 126 = 2 + 4 + 8 + 16 + 32 + 64
       /* $this->input =
            ["shiny gold bags contain 2 dark red bags.", // 2
            "dark red bags contain 2 dark orange bags.", // 4
            "dark orange bags contain 2 dark yellow bags.", // 8
            "dark yellow bags contain 2 dark green bags.", // 16
            "dark green bags contain 2 dark blue bags.", // 32
            "dark blue bags contain 2 dark violet bags.", // 64
            "dark violet bags contain no other bags.",]; // 0*/

        //$inputArr = [2, 4, 8, 16, 32, 64];


        $targets = [ 'shiny gold'];
        $bagCounts = [];
        foreach($bags['shiny gold'] as $nextBag => $count) {
            // 2. traverse the bag tree for $firstLevelBag
            // accumulating the count for this bag
            while($childBag = ($bags[$nextBag] ?? null)) {
                $children = array_keys($childBag);
                $counts = array_keys($childBag);
                print_r($childBag);
            }
        }
        while(true) {
            if (null === ($target = array_pop($targets))) {
                break;
            }
            $currTarget = $bags[$target];
            foreach($currTarget as $nextBag => $count) {
                $bagCounts[] = $count * ($bagCounts[count($bagCounts) - 1] ?? 1); // multiply the previous count with current and add that
                $targets[] = $nextBag;
            }
            print_r($currTarget);
            //$possibleBags =
            // with target, we need to traverse every possible bag it contains
            // shiny gold -> dark red -> dark orange


        }
        /*while(($target = array_pop($targets)) && !empty($bags[$target])) {
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

        //}

        return array_sum($bagCounts);
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
