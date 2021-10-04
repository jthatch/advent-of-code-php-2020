<?php

declare(strict_types=1);

namespace App\Day12;

interface WaypointInterface
{
    public const TRAVEL_FORWARD = 'F';

    public const ROTATION_LEFT  = 'L';
    public const ROTATION_RIGHT = 'R';

    public const DIRECTION_NORTH = 'N'; // +lat
    public const DIRECTION_EAST  = 'E'; // +long
    public const DIRECTION_SOUTH = 'S'; // -lat
    public const DIRECTION_WEST  = 'W'; // -long

    public const COMPASS = [
        0   => self::DIRECTION_EAST,
        90  => self::DIRECTION_SOUTH,
        180 => self::DIRECTION_WEST,
        270 => self::DIRECTION_NORTH,
    ];

    public function move(string $direction, int $amount): self;

    public function rotate(string $rotation, int $degrees): string;

    public function latitude(): int;

    public function longitude(): int;
}
