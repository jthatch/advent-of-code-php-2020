<?php

declare(strict_types=1);

namespace App;

use App\Interfaces\DayInterface;

class Day11 extends DayBehaviour implements DayInterface
{
    protected function applyRulesToSeats(array $seats): array
    {
        return [];
    }

    /**
     * All decisions are based on the number of occupied seats adjacent to a given seat
     * (one of the eight positions immediately up, down, left, right, or diagonal from the seat).
     * The following rules are applied to every seat simultaneously:.
     *
     * @return int|null
     */
    public function solvePart1(): ?int
    {
        $this->input = [
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
        ];
        $this->input = array_map(static fn (string $s): string => trim($s), $this->input);
        $i           = 0;
        /*while(true) {
            $seatsChanged = 0;

            foreach($this->input as &$row) {
                $adjacent = '';
                array_walk($row, static function(string $s) use (&$seatsChanged) {

                });
            }
        }
        print_r($this->input);*/
        return null;
    }

    public function solvePart2(): ?int
    {
        // TODO: Implement solvePart2() method.
        return null;
    }
}
