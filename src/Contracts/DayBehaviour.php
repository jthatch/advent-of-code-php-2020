<?php

declare(strict_types=1);

namespace App\Contracts;

abstract class DayBehaviour implements DayInterface
{
    public function __construct(protected array $input)
    {
    }

    public function day(): string
    {
        return (new \ReflectionClass($this))->getShortName();
    }
}
