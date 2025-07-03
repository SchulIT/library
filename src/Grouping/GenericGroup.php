<?php

namespace App\Grouping;

class GenericGroup implements GroupInterface {

    public mixed $key;

    public array $items = [ ];

    public function __construct(mixed $key) {
        $this->key = $key;
    }

    public function getKey(): mixed {
        return $this->key;
    }

    public function addItem($item): void {
        $this->items[] = $item;
    }
}