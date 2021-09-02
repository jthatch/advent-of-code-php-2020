<?php

declare(strict_types=1);

namespace App;

use App\Interfaces\DayInterface;
use App\Interfaces\ItemInterface;

class DayFactory
{
    public static function create(int $day): DayInterface
    {
        $name = strtolower($item->name);

        return match (true) {
            str_contains($name, 'backstage passes') => new BackstagePassItem($item),
            str_contains($name, 'aged brie')        => new AgedBrieItem($item),
            str_contains($name, 'sulfuras')         => new SulfurasItem($item),
            str_contains($name, 'conjured')         => new ConjuredItem($item),
            str_contains($name, 'normal')           => new NormalItem($item),

            default => $item
        };
    }

    public static function createBulk(array $items): array|ItemInterface
    {
        return array_map(static fn (Item $item) => static::create($item), $items);
    }
}
