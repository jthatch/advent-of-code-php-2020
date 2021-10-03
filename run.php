<?php

declare(strict_types=1);

use App\DayFactory;
use App\Interfaces\DayInterface;

$totalStartTime = microtime(true);
require 'vendor/autoload.php';

// If a day is passed on the command line, e.g. `php run.php 1` our generator returns that single day,
// otherwise returns all days that we have solved
$dayGenerator = $argv[1] ?? null
    ? (static fn () => yield DayFactory::create((int) $argv[1]))()
    : DayFactory::allAvailableDays();

printf("\033[32m---------------------------------------------------------------------------\n  Advent of Code 2020 PHP - James Thatcher\n---------------------------------------------------------------------------\033[0m\n");

/** @var DayInterface $day */
foreach ($dayGenerator as $day) {
    $startMemory = memory_get_usage();
    $startTime   = microtime(true);

    printf("\e[1;4m%s\e[0m\n", $day->day());
    printf("    Part1: \e[1;32m%s\e[0m\n", $day->solvePart1());
    printf("    Part2: \e[1;32m%s\e[0m\n", $day->solvePart2());
    report($startTime, $startMemory);
}

printf("\nTotal time: \e[2m%.5fs\e[0m\n", microtime(true) - $totalStartTime);

function report(float $startTime, int $startMemory): void
{
    $time           = microtime(true) - $startTime;
    $mem            = memory_get_usage() - $startMemory;
    $timeColourised = match (true) {
        $time >= 0.75 => sprintf("\e[0;31m%.5fs\e[2m", $time),
        $time >= 0.1  => sprintf("\e[1;31m%.5fs\e[2m", $time),
        default       => sprintf('%.5fs', $time),
    };
    $memColourised = match (true) {
        $mem >= 100000 => sprintf("\e[0;31m%s\e[0;2m", str_pad(humanReadableBytes($mem), 5)),
        $mem >= 1000   => sprintf("\e[1;31m%s\e[0;2m", str_pad(humanReadableBytes($mem), 5)),
        default        => sprintf('%s', str_pad(humanReadableBytes($mem), 5)),
    };

    printf("\e[2mMem: %s Peak: %s Time: %s\e[0m\n",
        $memColourised,
        str_pad(humanReadableBytes(memory_get_peak_usage()), 5),
        $timeColourised,
    );
}

function humanReadableBytes(int $bytes, ?int $precision = null): string
{
    $units          = ['b', 'kb', 'mb', 'gb', 'tb', 'pb', 'eb', 'zb', 'yb'];
    $precisionUnits = [0, 0, 1, 2, 2, 3, 3, 4, 4];
    $next           = 1024;
    for ($i = 0; ($bytes / $next) >= 0.9 && $i < count($units); ++$i) {
        $bytes /= $next;
    }

    return round($bytes, is_null($precision) ? $precisionUnits[$i] : $precision).$units[$i];
}
