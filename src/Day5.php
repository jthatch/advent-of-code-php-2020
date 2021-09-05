<?php

declare(strict_types=1);

namespace App;

use App\Interfaces\DayInterface;

class Day5 extends DayBehaviour implements DayInterface
{
    protected function solvePart1ReturningSeats(): array
    {
        $seatIds = [];
        foreach ($this->input as $input) {
            [$rowSeatGrid, $colSeatGrid] = array_chunk(str_split(trim($input)), 7);

            // This represents the possible range of rows on a plane
            $rowRange = [0, 127];
            $lastRow  = count($rowSeatGrid) - 1;
            // Loop over each row seat grid, picking the lower or upper half of the difference between the rowRange
            // ultimately you'll end up with a row number e.g. [45, 45], with 45 being the row ID
            array_map(static function (int $i, string $region) use (&$rowRange, $lastRow): void {
                $rowRange = match ($region) {
                    'F' => $i !== $lastRow
                        // take the lower half
                        ? [$rowRange[0], (int) ($rowRange[1] - ($rowRange[1] - $rowRange[0]) / 2)]
                        // final row, keep lower of the two
                        : min($rowRange),
                    'B' => $i !== $lastRow
                        // take the upper half
                        ? [(int) ($rowRange[0] + ceil(abs($rowRange[0] - $rowRange[1]) / 2)), $rowRange[1]]
                        // final row, keep higher of the two
                        : max($rowRange)
                };
            }, array_keys($rowSeatGrid), $rowSeatGrid);

            // now calculate columns
            $colRange = [0, 7];
            $lastCol  = count($colSeatGrid) - 1;
            array_map(static function (int $i, string $region) use (&$colRange, $lastCol): void {
                $colRange = match ($region) {
                    'L' => $i !== $lastCol
                        // take the lower half
                        ? [$colRange[0], (int) ($colRange[1] - ($colRange[1] - $colRange[0]) / 2)]
                        // final col, keep lower of the two
                        : min($colRange),
                    'R' => $i !== $lastCol
                        // take the upper half
                        ? [(int) ($colRange[0] + ceil(abs($colRange[0] - $colRange[1]) / 2)), $colRange[1]]
                        // final col, keep higher of the two
                        : max($colRange)
                };
            }, array_keys($colSeatGrid), $colSeatGrid);

            //Every seat also has a unique seat ID:
            // multiply the row by 8, then add the column. In this example, the seat has ID 44 * 8 + 5 = 357.
            $uniqueSeatId = ($rowRange * 8) + $colRange;
            // to help with part 2, we return seatId, row and col range indexed by the input instruction
            $seatIds[trim($input)] = [$uniqueSeatId, $rowRange, $colRange];
        }

        return $seatIds;
    }

    public function solvePart1(): ?int
    {
        return max(array_map(static fn ($s) => $s[0], $this->solvePart1ReturningSeats()));
    }

    /**
     * this one was hard. after a few failed attempts I realised my solution in part1 had bugs.
     * The solution was to chop the decimal point when taking the lower half, but ceil() when taking the upper half.
     * Once i solved that, my seatId's become a nice uniform (except one) incrementing list, whereas before there
     * were a lot of duplicates.
     * it took breaking it down into an multi-dimensional array of rows vs columns to see my mistake.
     * Once i refactored part1 it became obvious which seat was missing.
     * I've left my working out for prosperity and in-case i need to refer back to some fancy sorting :)
     * @return int|null
     */
    public function solvePart2(): ?int
    {
        $seatIds = array_unique(array_values(array_map(static fn ($s) => $s[0], $this->solvePart1ReturningSeats())));
        sort($seatIds);
        [$first, $last] = [$seatIds[0], max($seatIds)];
        for ($i = $first; $i < $last; ++$i) {
            if (!(in_array($i, $seatIds, true))) {
                return $i;
            }
        }

        return null;
        // a failed earlier attempt!
        //
        // we are looking for a missing seatId with seats either side that isn't in the front or back of the plane
        // assuming 0-128 rows and 0-8 columns, we are looking for a row x col that's missing from our list
        // we can then work out the seatId later
        /*   $allSeats = $this->solvePart1ReturningSeats();
           // first sort
           uasort($allSeats, fn($a, $b) => $a[0] <=> $b[0]);
           $seats = [];
           array_walk($allSeats, function($s) use (&$seats) {
               [$id, $row, $col] = $s;
               $seats[$row] ??= [];
               $seats[$row][$col] = $id;
           });
           ksort($seats);
           print_r($seats);*/
    }
}
