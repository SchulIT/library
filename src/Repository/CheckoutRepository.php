<?php

namespace App\Repository;

use App\Entity\Borrower;
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

    public function findActiveByBorrower(Borrower $borrower): array {
        return $this->em->createQueryBuilder()
            ->select(['c'])
            ->from(Checkout::class, 'c')
            ->leftJoin('c.borrower', 'b')
            ->where('c.borrower = :borrower')
            ->andWhere('c.end IS NULL')
            ->setParameter('borrower', $borrower)
            ->getQuery()
            ->getResult();
    }

    public function countActive(): int {
        return $this->em->createQueryBuilder()
            ->select('COUNT(1)')
            ->from(Checkout::class, 'c')
            ->where('c.end IS NULL')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countAll(): int {
        return $this->em->createQueryBuilder()
            ->select('COUNT(1)')
            ->from(Checkout::class, 'c')
            ->getQuery()
            ->getSingleScalarResult();
    }
}