<?php

declare(strict_types=1);

namespace App\Day12;

class Waypoint implements WaypointInterface
{
    public function __construct(protected int $latitude = 0, protected int $longitude = 0)
    {
    }

    public function latitude(): int
    {
        return $this->latitude;
    }

    public function longitude(): int
    {
        return $this->longitude;
    }

    public function move(string $direction, int $amount): self
    {
        switch ($direction) {
            case self::DIRECTION_NORTH: $this->latitude += $amount; break;
            case self::DIRECTION_SOUTH: $this->latitude -= $amount; break;
            case self::DIRECTION_EAST:  $this->longitude += $amount; break;
            case self::DIRECTION_WEST:  $this->longitude -= $amount; break;
            default:
                throw new \InvalidArgumentException("Invalid move() direction: {$direction}");
        }

        return $this;
    }

    public function rotate(string $rotation, int $degrees): string
    {
        $degrees = static::ROTATION_LEFT === $rotation
            ? 360 - $degrees
            : $degrees + 360 % 360;
        $degrees = 360 === $degrees ? 0 : $degrees;

        $direction = static::COMPASS[$degrees];

        [$this->latitude, $this->longitude] = match ($direction) {
            //   0: lat = lat, long = long
            static::DIRECTION_EAST => [$this->latitude, $this->longitude],
            //  90: lat = -long, long = lat
            static::DIRECTION_SOUTH => [-$this->longitude, $this->latitude],
            // 180 lat = -lat, long = -long
            static::DIRECTION_WEST => [-$this->latitude, -$this->longitude],
            // 270 lat = long, long - -lat
            static::DIRECTION_NORTH => [$this->longitude, -$this->latitude],
            default                 => [0, 0],
        };

        return $direction;
    }
}
