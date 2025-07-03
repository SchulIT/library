<?php

namespace App\Grouping;

class CallableStrategy implements GroupingStrategyInterface {



    public function computeKey(mixed $object, array $options = []): mixed {
        return $options['key']($object);
    }

    public function areEqualKeys(mixed $keyA, mixed $keyB, array $options = []): bool {
        return $options['equals']($keyA, $keyB);
    }

    public function createGroup(mixed $key, array $options = []): GroupInterface {
        return new GenericGroup($key);
    }
}