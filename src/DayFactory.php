<?php

declare(strict_types=1);

namespace App;

use App\Exceptions\DayClassNotFoundException;
use App\Exceptions\DayInputNotFoundException;
use App\Interfaces\DayInterface;

class DayFactory
{
    protected const MAX_DAYS     = 25;
    protected const CLASS_FORMAT = 'Day%d';
    protected const INPUT_FORMAT = __DIR__.'/../input/day%d.txt';

    public static function create(int $dayNumber): DayInterface
    {
        $dayClassName = static::getDayClass($dayNumber);
        $dayInputName = static::getDayInput($dayNumber);

        $dayInput = file($dayInputName) // this includes new lines as we want the raw input
            ?? throw new DayInputNotFoundException("Input file not found: {$dayInputName}", $dayNumber);

        return new $dayClassName($dayInput)
            ?? throw new DayClassNotFoundException("Missing day class: {$dayClassName}");
    }

    public static function createAllDaysCompleted(): array
    {
        $daysCompleted = [];
        foreach (range(1, static::MAX_DAYS) as $dayNumber) {
            try {
                $daysCompleted[] = static::create($dayNumber);
            } catch (DayClassNotFoundException|DayInputNotFoundException) {
                break;
            }
        }

        return $daysCompleted;
    }

    private static function getDayClass(int $dayNumber): string
    {
        return __NAMESPACE__.'\\'.sprintf(static::CLASS_FORMAT, $dayNumber);
    }

    private static function getDayInput(int $dayNumber): string
    {
        return sprintf(static::INPUT_FORMAT, $dayNumber);
    }
}
