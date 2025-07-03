<?php

namespace App\Student\Selector;

readonly class Grade {

    public function __construct(public string $name, public array $students) { }
}