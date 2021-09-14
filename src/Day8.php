<?php

declare(strict_types=1);

namespace App;

use App\Interfaces\DayInterface;

class Day8 extends DayBehaviour implements DayInterface
{
    protected function getInputAsArray(): array
    {
        return array_map(static function (string $s) {
            $row = array_combine(['ins', 'val'], explode(' ', $s));
            $row['val'] = (int) trim($row['val']);

            return $row;
        }, $this->input);
    }

    /**
     * Run your copy of the boot code.
     * Immediately before any instruction is executed a second time, what value is in the accumulator?
     *
     * @return int|null
     */
    public function solvePart1(): ?int
    {
        $accumulator = 0;
        $insPointer  = 0;
        $input       = $this->getInputAsArray();
        $inputLen    = count($input);

        while (true) {
            $i = &$input[$insPointer];
            // if we've run this instruction before, exit to avoid infinite loop
            if (isset($i['done']) || $insPointer > $inputLen) {
                break;
            }

            switch ($i['ins']) {
                case 'nop':
                    $insPointer++;
                    break;
                case 'acc':
                    $accumulator += $i['val'];
                    ++$insPointer;
                    break;
                case 'jmp':
                    $insPointer += $i['val'];
                    break;
            }

            $i['done'] = true;
        }

        return $accumulator;
    }

    public function solvePart2(): ?int
    {
        // TODO: Implement solvePart2() method.

        return null;
    }
}
