<?php

declare(strict_types=1);

namespace App;

use App\Interfaces\DayInterface;

class Day11 extends DayBehaviour implements DayInterface
{
    protected int $i = 0;

    /**
     * All decisions are based on the number of occupied seats adjacent to a given seat
     * (one of the eight positions immediately up, down, left, right, or diagonal from the seat).
     * The following rules are applied to every seat simultaneously:.
     * Rules:
     * If a seat is empty (L) and there are no occupied seats adjacent to it, the seat becomes occupied.
     * If a seat is occupied (#) and four or more seats adjacent to it are also occupied, the seat becomes empty.
     *
     * Simulate your seating area by applying the seating rules repeatedly until no seats change state. How many seats end up occupied?
     *
     * @return int|null
     */
    public function solvePart1(): ?int
    {
        /*$this->input = [
            'L.LL.LL.LL',
            'LLLLLLL.LL',
            'L.L.L..L..',
            'LLLL.LL.LL',
            'L.LL.LL.LL',
            'L.LLLLL.LL',
            '..L.L.....',
            'LLLLLLLLLL',
            'L.LLLLLL.L',
            'L.LLLLL.LL',
        ];*/
        // convert into [y][x] array
        $this->input      = array_map(static fn (string $s): array => str_split(trim($s)), $this->input);
        $finalSeatingPlan = $this->seatTraverse();
        $occupied         = array_filter(array_merge(...$finalSeatingPlan), static fn (string $s) => '#' === $s);

        return count($occupied);
    }

    protected function seatTraverse(?array $seatLayout = null): array
    {
        ++$this->i;
        // final recursive check
        if ($seatLayout === $this->input) {
            return $this->input;
        }

        // if we haven't been seeded then start with input
        $this->input = $seatLayout ?? $this->input;

        $newInput = $this->input; // clone it, we'll make all changes to the new input
        for ($y = 0, $yMax = count($this->input); $y < $yMax; ++$y) {
            for ($x = 0, $xMax = count($this->input[$y]); $x < $xMax; ++$x) {
                $adjacentSeats = [
                    // above
                    $this->input[$y - 1][$x - 1] ?? '',
                    $this->input[$y - 1][$x] ?? '',
                    $this->input[$y - 1][$x + 1] ?? '',
                    // below
                    $this->input[$y + 1][$x - 1] ?? '',
                    $this->input[$y + 1][$x] ?? '',
                    $this->input[$y + 1][$x + 1] ?? '',
                    // left
                    $this->input[$y][$x - 1] ?? '',
                    // right
                    $this->input[$y][$x + 1] ?? '',
                ];
                $occupiedAdjacent = array_filter($adjacentSeats, static fn (string $v) => '#' === $v);
                $seat             = $this->input[$y][$x];
                switch ($seat) { // faster than using match
                    case '.':
                        continue 2;
                    case 'L':
                        if (empty($occupiedAdjacent)) {
                            $newInput[$y][$x] = '#';
                        }
                        break;
                    case '#':
                        if (4 <= count($occupiedAdjacent)) {
                            $newInput[$y][$x] = 'L';
                        }
                        break;
                }
            }
            /*printf("y: %d\n%s\n%s\n",
                $y,
                implode('', $this->input[$y]),
                implode('', $newInput[$y]),
            );*/
        }
        /*printf("%d: new: %d old: %d\n",
            $this->i,
            count(array_filter(array_merge(...$newInput), static fn (string $s) => '#' === $s)),
            count(array_filter(array_merge(...$this->input), static fn (string $s) => '#' === $s)),
        );*/

        return $this->seatTraverse($newInput);
    }

    public function solvePart2(): ?int
    {
        // TODO: Implement solvePart2() method.
        return null;
    }
}
