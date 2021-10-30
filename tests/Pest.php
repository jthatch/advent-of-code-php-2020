<?php

declare(strict_types=1);

function getDayFromFile(string $file): ?int
{
    preg_match('/Day(\d{1,2})Test/', $file, $matches);

    return ($matches[1] ?? null) ? (int) $matches[1] : null;
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

uses()->beforeEach(function (): void {
    $this->startTime = microtime(true);
    $this->startMemory = memory_get_usage();
})->in(__DIR__);

uses()->afterEach(function (): void {
    printf(
        "\tCompleted in: %.6fs Memory: %s Peak: %s\n",
        microtime(true) - $this->startTime,
        humanReadableBytes(memory_get_usage() - $this->startMemory),
        humanReadableBytes(memory_get_peak_usage())
    );
})->in(__DIR__);
