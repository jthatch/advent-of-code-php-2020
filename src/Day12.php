<?php

declare(strict_types=1);

namespace App;

use App\Interfaces\DayInterface;

class Day12 extends DayBehaviour implements DayInterface
{
    private int $degrees = 90;

    public function solvePart1(): ?int
    {
        $this->input = array_map(static fn (string $s): string => trim($s), $this->input);

        $dist = [
            'N' => 0,
            'E' => 0,
            'S' => 0,
            'W' => 0,
        ];
        $direction = 'E';
        foreach ($this->input as $ins) {
            $action = $ins[0];
            $value  = (int) substr($ins, 1);
            if ('L' === $action || 'R' === $action) {
                $direction = $this->getDirectionFromTurn($ins);
            } elseif ('F' === $action) {
                $dist[$direction] += $value;
            } else {
                $dist[$action] += $value;
            }
        }
        // calculate manhattan distance
        $EW = [$dist['E'], $dist['W']];
        $NE = [$dist['N'], $dist['S']];

        return (max($EW) - min($EW)) + (max($NE) - min($NE));
    }

    protected function getDirectionFromTurn(string $turn): string
    {
        $action        = $turn[0];
        $degrees       = (int) substr($turn, 1);
        $this->degrees = 'R' === $action
            ? $this->degrees + $degrees
            : $this->degrees - $degrees;
        if ($this->degrees > 360) {
            $this->degrees -= 360;
        }
        if ($this->degrees < 0) {
            $this->degrees += 360;
        }
        if (360 === $this->degrees) {
            $this->degrees = 0;
        }
        $compass = ['N', 'E', 'S', 'W'];

        return $compass[round($this->degrees / 90)];
    }

    public function solvePart2(): ?int
    {
        /*$this->input = [
            'F10',
            'N3',
            'F7',
            'R90',
            'F11'
        ];*/
        // TODO: Implement solvePart2() method.
        return null;
    }
}
