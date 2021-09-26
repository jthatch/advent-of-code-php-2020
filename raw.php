<?php
declare(strict_types=1);

use App\DayFactory;
use App\Interfaces\DayInterface;

require 'vendor/autoload.php';

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

printf("Advent of code 2020 PHP solutions - github.com/jthatch\n\n");

/** @var DayInterface $day */
foreach(DayFactory::allAvailableDays() as $day) {
    $startTime = microtime(true);
    $startMemory = memory_get_usage();

    printf("%s\n", $day->day());
    printf("  part1: %s\n", $day->solvePart1());
    printf("  part2: %s\n", $day->solvePart2());
    printf("\tCompleted in: %.6fs Memory: %s Peak: %s\n",
        microtime(true) - $startTime,
        humanReadableBytes(memory_get_usage() - $startMemory),
        humanReadableBytes(memory_get_peak_usage())
    );
}