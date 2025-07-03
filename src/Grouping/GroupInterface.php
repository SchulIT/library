<?php

namespace App\Grouping;

interface GroupInterface {

    public function getKey(): mixed;

    public function addItem($item): void;
}