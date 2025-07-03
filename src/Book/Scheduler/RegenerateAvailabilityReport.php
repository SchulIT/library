<?php

namespace App\Book\Scheduler;

use Symfony\Component\Messenger\Attribute\AsMessage;

#[AsMessage('async')]
readonly class RegenerateAvailabilityReport {
    public function __construct(private int $bookId) { }

    public function getBookId(): int {
        return $this->bookId;
    }
}