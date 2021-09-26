<?php
declare(strict_types=1);

use App\DayFactory;
use App\Interfaces\DayInterface;

$totalStartTime = microtime(true);
require 'vendor/autoload.php';

printf("\033[32m---------------------------------------------------------------------------\n  Advent of Code 2020 - James Thatcher\n---------------------------------------------------------------------------\033[0m\n");

/** @var DayInterface $day */
foreach(DayFactory::allAvailableDays() as $day) {
    $startTime = microtime(true);
    $startMemory = memory_get_usage();

    printf("\e[1;4m%s\e[0m\n", $day->day());
    printf("    part1: \e[1m%s\e[0m\n", $day->solvePart1());
    printf("    part2: \e[1m%s\e[0m\n", $day->solvePart2());
    printf("\e[2mmem: %s peak: %s took: %.5fs\e[0m\n",
        humanReadableBytes(memory_get_usage() - $startMemory),
        humanReadableBytes(memory_get_peak_usage()),
        microtime(true) - $startTime,
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

printf("\nTotal time: \e[2m%.5fs\e[0m\n", microtime(true) - $totalStartTime);
