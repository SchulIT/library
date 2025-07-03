<?php

namespace App\Student\Selector;

use App\Sorting\SortingStrategyInterface;
use App\Sorting\StringStrategy;

/**
 * @implements SortingStrategyInterface<Grade>
 */
readonly class GradeSortStrategy implements SortingStrategyInterface {

    public function __construct(private StringStrategy $strategy) { }

    public function compare($objectA, $objectB): int {
        return $this->strategy->compare($objectA->name, $objectB->name);
    }
}