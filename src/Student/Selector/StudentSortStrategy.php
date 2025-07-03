<?php

namespace App\Student\Selector;

use App\Sorting\SortingStrategyInterface;
use App\Sorting\StringStrategy;

/**
 * @implements SortingStrategyInterface<Student>
 */
class StudentSortStrategy implements SortingStrategyInterface {

    public function __construct(private StringStrategy $strategy) { }

    public function compare($objectA, $objectB): int {
        $firstnameCmp = $this->strategy->compare($objectA->firstname, $objectB->firstname);

        if($firstnameCmp === 0) {
            return $this->strategy->compare($objectA->lastname, $objectB->lastname);
        }

        return $firstnameCmp;
    }
}