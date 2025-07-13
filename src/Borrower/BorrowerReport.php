<?php

namespace App\Borrower;

readonly class BorrowerReport {
    public function __construct(public int $totalCheckouts,
                                public int $activeCheckouts) { }
}