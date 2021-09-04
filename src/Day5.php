<?php

declare(strict_types=1);

namespace App;

use App\Interfaces\DayInterface;

class Day5 extends DayBehaviour implements DayInterface
{
    public function solvePart1(): ?int
    {
        /*$this->input = [
            //'FBFBBFFRLR', // 44 * 8 + 5 = 357.
            //'BFFFBBFRRR', //: row 70, column 7, seat ID 567.
            //'FFFBBBFRRR', //: row 14, column 7, seat ID 119.
            //'BBFFBBFRLL', //row 102, column 4, seat ID 820.
        ];*/
        $seatIds = [];
        foreach ($this->input as $input) {
            [$rowSeatGrid, $colSeatGrid] = array_chunk(str_split(trim($input)), 7);

            // This represents the possible range of rows on a plane
            $rowRange = [0, 127];
            $lastRow  = count($rowSeatGrid) - 1;
            // Loop over each row seat grid, picking the lower or upper half of the difference between the rowRange
            // ultimately you'll end up with a row number e.g. [45, 45], with 45 being the row ID
            array_map(static function (int $i, string $region) use (&$rowRange, $lastRow): void {
                if (0 === $i && 'B' === $region) {
                    $rowRange[0] = 1;
                }
                $rowRange = match ($region) {
                    'F' => $i !== $lastRow
                        // take the lower half
                        ? [$rowRange[0], (int) ($rowRange[1] - floor(($rowRange[1] - $rowRange[0]) / 2))]
                        // final row, keep lower of the two
                        : (int) ($rowRange[1] - floor(($rowRange[1] - $rowRange[0]) / 2)),
                    'B' => $i !== $lastRow
                        // take the upper half
                        ? [(int) ($rowRange[0] + ceil(abs($rowRange[0] - $rowRange[1])) / 2), $rowRange[1]]
                        // final row, keep higher of the two
                        : (int) ($rowRange[0] + ceil(abs($rowRange[0] - $rowRange[1])) / 2)
                };
            }, array_keys($rowSeatGrid), $rowSeatGrid);

            // now calculate columns
            $colRange = [0, 7];
            $lastCol  = count($colSeatGrid) - 1;
            array_map(static function (int $i, string $region) use (&$colRange, $lastCol): void {
                if (0 === $i && 'R' === $region) {
                    $colRange[0] = 1;
                }
                $colRange = match ($region) {
                    'L' => $i !== $lastCol
                        // take the lower half
                        ? [$colRange[0], (int) ($colRange[1] - (($colRange[1] - $colRange[0]) / 2))]
                        // final row, keep lower of the two
                        : min($colRange[0], $colRange[1]),
                    'R' => $i !== $lastCol
                        // take the upper half
                        ? [(int) ($colRange[0] + (abs($colRange[0] - $colRange[1])) / 2), $colRange[1]]
                        // final row, keep higher of the two
                        : max($colRange[1], $colRange[1])
                };
            }, array_keys($colSeatGrid), $colSeatGrid);

            //Every seat also has a unique seat ID:
            // multiply the row by 8, then add the column. In this example, the seat has ID 44 * 8 + 5 = 357.
            $uniqueSeatId = ($rowRange * 8) + $colRange;
            $seatIds[]    = $uniqueSeatId;
        }

        return max($seatIds);
    }

    public function solvePart2(): ?int
    {
        // TODO: Implement solvePart2() method.

        return null;
    }
}
