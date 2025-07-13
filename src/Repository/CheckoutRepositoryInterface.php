<?php

namespace App\Repository;

use App\Entity\Borrower;
use App\Entity\Checkout;

interface CheckoutRepositoryInterface {

    /**
     * @param Borrower $borrower
     * @return Checkout[]
     */
    public function findActiveByBorrower(Borrower $borrower): array;

    public function countActive(): int;

    public function countAll(): int;

    public function persist(Checkout $checkout): void;
    public function remove(Checkout $checkout): void;
}