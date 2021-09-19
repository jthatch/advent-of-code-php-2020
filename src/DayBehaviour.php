<?php

declare(strict_types=1);

namespace App;

abstract class DayBehaviour
{
    public function __construct(protected array $input)
    {
    }

    protected function inputAsInt(): array
    {
        return array_map(static fn (string $s): int => (int) trim($s), $this->input);
    }
}
