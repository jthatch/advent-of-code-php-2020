<?php

declare(strict_types=1);

use App\DayFactory;
use App\Interfaces\DayInterface;

uses()->beforeEach(function (): void {
    /* @var DayInterface day */
    $this->day = DayFactory::create(getDayFromFile(__FILE__));
});

test('solves part1')
    ->expect(fn () => $this->day->solvePart1())
    ->toBe(1753)
;

test('solves part2')
    ->expect(fn () => $this->day->solvePart2())
    ->toBe(733)
;
