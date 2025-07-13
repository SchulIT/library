<?php

namespace App\Borrower\Scheduler;

use Symfony\Component\Messenger\Attribute\AsMessage;

#[AsMessage('async')]
readonly class GenerateBorrowerReport {
    public function __construct(private int $borrowerId) { }

    public function getBorrowerId(): int {
        return $this->borrowerId;
    }
}

