<?php

declare(strict_types=1);

namespace App;

use App\Contracts\DayBehaviour;
use App\Day12\Ship;
use App\Day12\ShipWithWaypoint;
use App\Day12\Waypoint;

class Day12 extends DayBehaviour
{
    protected function getInputAsDirectionAmountArr(): array
    {
        // split each instruction into [Direction,Amount] e.g. [['R', 90], ..]
        return array_map(static fn (string $s): array => [$s[0], (int) substr(trim($s), 1)], $this->input);
    }

    public function solvePart1(): ?int
    {
        $input = $this->getInputAsDirectionAmountArr();
        $ship  = new Ship(
            degrees: 0
        );
        array_walk($input, static fn (array $i) => $ship->move($i[0], $i[1]));

        return $ship->manhattanDistance();
    }

    public function solvePart2(): ?int
    {
        $input    = $this->getInputAsDirectionAmountArr();
        $waypoint = new Waypoint(
            latitude: 1,
            longitude: 10
        );
        $ship = new ShipWithWaypoint(waypoint: $waypoint);
        array_walk($input, static fn (array $i) => $ship->move($i[0], $i[1]));

        return $ship->manhattanDistance();
    }
}
