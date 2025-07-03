<?php

namespace App\Repository;

use App\Entity\Checkout;

interface CheckoutRepositoryInterface {
    public function persist(Checkout $checkout): void;
    public function remove(Checkout $checkout): void;
}