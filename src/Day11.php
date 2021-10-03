<?php

declare(strict_types=1);

namespace App;

use App\Interfaces\DayInterface;

class Day11 extends DayBehaviour implements DayInterface
{
    protected array $seats;
    private int $yMax;
    private int $xMax;
    // [[y,x],..] each of the 8 possible adjacent seats, starting top left going clockwise
    private array $adjacent = [[-1, -1], [-1, 0], [-1, 1], [0, 1], [1, 1], [1, 0], [1, -1], [0, -1]];

    public function solvePart1(): ?int
    {
        // convert into [y][x] grid
        $this->seats = array_map(static fn (string $s): array => str_split(trim($s)), $this->input);
        $this->yMax  = count($this->seats); // rows
        $this->xMax  = count($this->seats[0]); // seats

        return count($this->seatTraversePart1());
    }

    protected function seatTraversePart1(?array $seatLayout = null): array
    {
        // final recursive check
        if ($seatLayout === $this->seats) {
            // return array of occupied seats
            return array_filter(array_merge(...$seatLayout), static fn (string $s) => '#' === $s);
        }

        // if we haven't been seeded then start with input
        $this->seats = $seatLayout ?? $this->seats;

        $newInput = $this->seats; // clone it, we'll make all changes to the new input
        for ($y = 0; $y < $this->yMax; ++$y) {
            for ($x = 0; $x < $this->xMax; ++$x) {
                $seat = $this->seats[$y][$x];

                if ('.' === $seat) { // skip empty seat, better to do this early to avoid additional computation
                    continue;
                }

                // loop over our adjacent positions, filtering only occupied seats
                $occupiedAdjacent = count(array_filter($this->adjacent, fn (array $pos) => '#' === ($this->seats[$y + $pos[0]][$x + $pos[1]] ?? '')));

                if ('L' === $seat && 0 === $occupiedAdjacent) {
                    $newInput[$y][$x] = '#';
                } elseif ('#' === $seat && 4 <= $occupiedAdjacent) {
                    $newInput[$y][$x] = 'L';
                }
            }
        }

        return $this->seatTraversePart1($newInput);
    }

    public function solvePart2(): ?int
    {
        $this->seats = array_map(static fn (string $s): array => str_split(trim($s)), $this->input);
        $this->yMax  = count($this->seats); // rows
        $this->xMax  = count($this->seats[0]); // seats

        return count($this->seatTraversePart2());
    }

    protected function seatTraversePart2(?array $seatLayout = null): array
    {
        // final recursive check
        if ($seatLayout === $this->seats) {
            // return array of occupied seats
            return array_filter(array_merge(...$seatLayout), static fn (string $s) => '#' === $s);
        }

        // if we haven't been seeded then start with input
        $this->seats = $seatLayout ?? $this->seats;

        $newInput = $this->seats; // clone it, we'll make all changes to the new input
        for ($y = 0; $y < $this->yMax; ++$y) {
            for ($x = 0; $x < $this->xMax; ++$x) {
                $seat = $this->seats[$y][$x];

                if ('.' === $seat) { // skip empty seat
                    continue;
                }

                // adjacent in part 2 is trickier, it's now line of sight. We must now traverse
                // in the adjacent direction until we encounter a chair or the wall
                $occupiedAdjacent = 0;
                array_filter($this->adjacent, function (array $pos) use ($y, $x, &$occupiedAdjacent) {
                    while (true) {
                        // keep travelling in the adjacent direction
                        $y += $pos[0];
                        $x += $pos[1];
                        // determine if we hit a wall
                        if ($y < 0 || $x < 0 || $y > ($this->yMax - 1) || $x > ($this->xMax - 1)) {
                            break; // we hit a wall
                        }
                        $seatAtPos = ($this->seats[$y][$x] ?? '');
                        if ('#' === $seatAtPos) {
                            ++$occupiedAdjacent;
                        }
                        if ('.' !== $seatAtPos) {
                            break;
                        }
                    }

                    return $occupiedAdjacent > 0;
                });

                if ('L' === $seat && 0 === $occupiedAdjacent) {
                    $newInput[$y][$x] = '#';
                } elseif ('#' === $seat && 5 <= $occupiedAdjacent) {
                    $newInput[$y][$x] = 'L';
                }
            }
        }

        return $this->seatTraversePart2($newInput);
    }
}
