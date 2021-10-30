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

    /**
     * Fix the program so that it terminates normally by changing exactly one jmp (to nop) or nop (to jmp).
     * What is the value of the accumulator after the program terminates?
     *
     * @return int|null
     */
    public function solvePart2(): ?int
    {
        $input       = $this->getInputAsArray();
        $accumulator = 0;
        $insPointer  = 0;

        // get a list of all nop and jmp instructions, preserving their index
        // if we hit an infinite loop we'll pop one of these of and swap the instruction around
        $nopAndJmp = array_filter($input, static fn ($i) => in_array($i['ins'], ['nop', 'jmp']));

        $regression = null;
        while (true) {
            $i = &$input[$insPointer];

            // natural finishing program, terminate and return accumulator
            if (null === $i) {
                break;
            }

            // we've entered an infinite loop, enter regression mode
            // swap the next jmp/nop instruction from the stack
            if (isset($i['done'])) {
                if (($pointer = array_key_last($nopAndJmp)) === null) {
                    // we've exhausted all options
                    break;
                }
                array_walk($input, static function (&$i): void {
                    unset($i['done']);
                }); // reset
                $regression            = array_pop($nopAndJmp);
                $regression['pointer'] = $pointer;
                $insPointer            = 0;
                $accumulator           = 0;
                continue;
            }

            /** @noinspection NestedTernaryOperatorInspection */
            // override our instruction if we're in regression
            $ins = ($regression['pointer'] ?? null) === $insPointer
                ? ('nop' === $regression['ins'] ? 'jmp' : 'nop')
                : $i['ins'];

            switch ($ins) {
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
}
