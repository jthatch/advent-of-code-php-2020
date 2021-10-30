<?php

declare(strict_types=1);

namespace App\Day12;

class Ship implements ShipInterface
{
    public function __construct(
        protected int $degrees,
        protected int $latitude = 0,
        protected int $longitude = 0
    ) {
    }

    public function move(string $direction, int $amount): self
    {
        switch ($direction) {
            case self::TRAVEL_FORWARD:  $this->move(self::COMPASS[$this->degrees], $amount); break;
            case self::DIRECTION_NORTH: $this->latitude += $amount; break;
            case self::DIRECTION_SOUTH: $this->latitude -= $amount; break;
            case self::DIRECTION_EAST:  $this->longitude += $amount; break;
            case self::DIRECTION_WEST:  $this->longitude -= $amount; break;
            case self::ROTATION_LEFT:
            case self::ROTATION_RIGHT:
                $this->rotate($direction, $amount);
                break;

            default:
                throw new \InvalidArgumentException("Invalid move() direction: {$direction}");
        }

        return $this;
    }

    public function rotate(string $rotation, int $degrees): string
    {
        $degreeMultiplier = static::ROTATE[$rotation]
            ?? throw new \InvalidArgumentException("Invalid rotate() rotation: {$rotation}.");
        $this->degrees = ($this->degrees + ($degrees * $degreeMultiplier) + 360) % 360;

        return static::COMPASS[$this->degrees];
    }

    public function manhattanDistance(): int
    {
        return abs($this->latitude) + abs($this->longitude);
    }
}
