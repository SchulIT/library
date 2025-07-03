<?php

namespace App\Book;

readonly class AvailabilityReport {
    public function __construct(public int $notAvailableCount, public int $checkedOutCount, public int $availableAndNotCheckedOut) {

    }
}