<?php

declare(strict_types=1);

use App\DayFactory;
use App\Interfaces\DayInterface;

uses()->beforeEach(function (): void {
    /* @var DayInterface day */
    $this->day = DayFactory::create(6);
});

test('solves part1')
    ->expect(fn () => $this->day->solvePart1())
    ->toBe(7110)
;

test('solves part2')->skip()
    ->expect(fn () => $this->day->solvePart2())
    ->toBe(699)
;
