<?php

declare(strict_types=1);

use App\Day12\Ship;
use App\Day12\ShipWithWaypoint;
use App\Day12\Waypoint;
use App\DayFactory;
use App\Interfaces\DayInterface;

uses()->beforeEach(function (): void {
    /* @var DayInterface day */
    $this->day = DayFactory::create(getDayFromFile(__FILE__));
});

test('solves part1')
    ->expect(fn () => $this->day->solvePart1())
    ->toBe(1148)
;

test('solves part2')
    ->expect(fn () => $this->day->solvePart2())
    ->toBe(52203)
;

test('Ship object can follow instructions', function (): void {
    $ship = new Ship(degrees: 0);
    $ship
        ->move('F', 10)
        ->move('N', 3)
        ->move('F', 7)
        ->move('R', 90)
        ->move('F', 11);

    expect($ship->manhattanDistance())->toBe(25);
});

test('ShipWithWaypoint object can follow instructions', function (): void {
    $waypoint = new Waypoint(1, 10);
    $ship = new ShipWithWaypoint($waypoint);
    $ship
        ->move('F', 10)
        ->move('N', 3)
        ->move('F', 7)
        ->move('R', 90)
        ->move('F', 11);

    expect($ship->manhattanDistance())->toBe(286);
});

test('Waypoint object can follow instructions', function (): void {
    $waypoint = new Waypoint(100, 10);
    $waypoint
        ->move('N', 3)
        ->move('S', 7)
        ->move('E', 91)
        ->move('W', 11);

    expect($waypoint->latitude())->toBe(96);
    expect($waypoint->longitude())->toBe(90);
    expect($waypoint->rotate('L', 90))->toBe('N');
    expect($waypoint->rotate('L', 180))->toBe('W');
    expect($waypoint->rotate('L', 270))->toBe('S');
    expect($waypoint->rotate('L', 360))->toBe('E');
    expect($waypoint->rotate('L', 0))->toBe('E');
    expect($waypoint->rotate('R', 90))->toBe('S');
    expect($waypoint->rotate('R', 180))->toBe('W');
    expect($waypoint->rotate('R', 270))->toBe('N');
    expect($waypoint->rotate('R', 0))->toBe('E');
});
