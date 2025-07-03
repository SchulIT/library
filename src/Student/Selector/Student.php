<?php

namespace App\Student\Selector;

readonly class Student {
    public function __construct(public int $id, public string $firstname, public string $lastname) { }
}