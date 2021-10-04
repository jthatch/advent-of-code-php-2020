<?php

declare(strict_types=1);

namespace App\Day12;

class ShipWithWaypoint implements ShipInterface
{
    public function __construct(protected Waypoint $waypoint,
                                protected int $latitude = 0,
                                protected int $longitude = 0)
    {
    }

    public function move(string $direction, int $amount): self
    {
        switch ($direction) {
            case static::DIRECTION_NORTH:
            case static::DIRECTION_SOUTH:
            case static::DIRECTION_EAST:
            case static::DIRECTION_WEST:
                $this->waypoint->move($direction, $amount);
                break;
            case static::TRAVEL_FORWARD:
                $this->latitude  += $this->waypoint->latitude()  * $amount;
                $this->longitude += $this->waypoint->longitude() * $amount;
                break;
            case static::ROTATION_LEFT:
            case static::ROTATION_RIGHT:
                $this->rotate($direction, $amount);
                break;

            default:
                throw new \InvalidArgumentException("Invalid move() direction: {$direction}");
        }

        return $this;
    }

    public function rotate(string $rotation, int $degrees): string
    {
        return $this->waypoint->rotate($rotation, $degrees);
    }

    public function manhattanDistance(): int
    {
        return abs($this->latitude) + abs($this->longitude);
    }
}
