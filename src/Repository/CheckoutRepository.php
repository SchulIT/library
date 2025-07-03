<?php

namespace App\Repository;

use App\Entity\Checkout;

class CheckoutRepository extends AbstractRepository implements CheckoutRepositoryInterface {

    public function persist(Checkout $checkout): void {
        $this->em->persist($checkout);
        $this->em->flush();
    }

    public function remove(Checkout $checkout): void {
        $this->em->remove($checkout);
        $this->em->flush();
    }
}