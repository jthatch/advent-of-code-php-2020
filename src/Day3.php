<?php

declare(strict_types=1);

namespace App;

use App\Interfaces\DayInterface;

class Day3 extends DayBehaviour implements DayInterface
{
    public function solvePart1(): ?int
    {
        // build multi-dimensional array of map
        $map      = array_map(static fn ($s) => str_split(trim($s)), $this->input);
        $tree     = '#';
        $hitTrees = 0;
        $x        = 3; // starting point
        //printf(implode('', $map[0])."\n");
        $rowLength = count(array_shift($map) ?? []);
        foreach ($map as $row) {
            $pixel = $row[$x];
            $hitTrees += $pixel === $tree ? 1 : 0;
            $row[$x] = $pixel   === $tree ? 'X' : 'O';
            //printf(implode('', $row)."\n");

            $x += 3;
            $x = $x >= $rowLength ? $x - $rowLength : $x;
        }

        return $hitTrees;
    }

    public function solvePart2(): ?int
    {
        // build multi-dimensional array of map
        $map       = array_map(static fn ($s) => str_split(trim($s)), $this->input);
        $rowLength = count($map[0]);
        $mapLength = count($map);
        $tree      = '#';
        $hitsTotal = [];
        $slopes    = [
            [1, 1], // right 1, down 1
            [3, 1], // right 3, down 1
            [5, 1], // right 5, down 1
            [7, 1], // right 7, down 1
            [1, 2], // right 1, down 2
        ];
        foreach ($slopes as [$right, $down]) {
            $hitTrees = 0;
            $x        = $right; // starting point
            for ($i = $down; $i < $mapLength; ++$i) {
                $row = $map[$i];
                if (0 !== $i % $down) {
                    continue; // skip according to our $down velocity
                }
                $pixel = $row[$x];
                $hitTrees += $pixel === $tree ? 1 : 0;
                $row[$x] = $pixel   === $tree ? 'X' : 'O';

                $x += $right;
                $x = $x >= $rowLength ? $x - $rowLength : $x;
            }
            $hitsTotal[] = $hitTrees;
        }

        return (int) array_product($hitsTotal);
    }
}
