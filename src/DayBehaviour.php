<?php

namespace App;

abstract class DayBehaviour
{
    public function __construct(protected string $input) {}
}