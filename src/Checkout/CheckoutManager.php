<?php

namespace App\Checkout;

use App\Entity\BookCopy;
use App\Entity\Borrower;
use App\Entity\Checkout;
use App\Repository\CheckoutRepositoryInterface;
use DateTime;

readonly class CheckoutManager {

    public function __construct(private CheckoutRepositoryInterface $repository) {

    }

    public function bulkCheckout(BulkCheckoutRequest $request): void {
        foreach($request->copies as $copy) {
            $this->checkout($copy, $request->borrower);
        }
    }

    public function checkout(BookCopy $copy, Borrower $borrower): void {
        if($copy->canCheckout() === false) {
            return;
        }

        /** @var Checkout|null $lastCheckout */
        $lastCheckout = $copy->getCheckouts()->first() ?? null;

        if($lastCheckout instanceof Checkout && $lastCheckout->getBorrower()->getId() === $borrower->getId()) {
            // no need for return
            return;
        }

        $this->return($copy);

        $newCheckout = (new Checkout())
            ->setBookCopy($copy)
            ->setBorrower($borrower)
            ->setStart(new DateTime());

        $this->repository->persist($newCheckout);
    }

    /**
     * @param BulkReturnRequest $request
     * @return Borrower|null
     */
    public function bulkReturn(BulkReturnRequest $request): ?Borrower {
        $borrowers = [ ];
        $borrowersCount = [ ];

        foreach($request->copies as $copy) {
            $borrower = $this->return($copy);

            if($borrower === null) {
                continue;
            }

            $borrowers[$borrower->getId()] = $borrower;

            if(!isset($borrowersCount[$borrower->getId()])) {
                $borrowersCount[$borrower->getId()] = 0;
            }

            $borrowersCount[$borrower->getId()]++;
        }

        arsort($borrowersCount);
        $firstKey = array_key_first($borrowersCount);

        return $borrowers[$firstKey] ?? null;
    }

    public function return(BookCopy $copy): ?Borrower {
        /** @var Checkout|null $lastCheckout */
        $lastCheckout = $copy->getCheckouts()->first() ?? null;

        if($lastCheckout === null || $lastCheckout === false) {
            return null;
        }

        $lastCheckout->setEnd(new DateTime());
        $this->repository->persist($lastCheckout);

        return $lastCheckout->getBorrower();
    }

    public function getStatus(BookCopy $copy): CheckoutStatus {
        if($copy->canCheckout() === false) {
            return CheckoutStatus::NotAvailable;
        }

        if($copy->getCheckouts()->count() === 0) {
            return CheckoutStatus::Available;
        }

        $latestCheckout = $copy->getCheckouts()->first();

        if($latestCheckout === null) {
            return CheckoutStatus::Available;
        }

        if($latestCheckout->getEnd() === null) {
            return CheckoutStatus::CheckedOut;
        }

        return CheckoutStatus::Available;
    }

    public function isCheckedOut(BookCopy $copy): bool {
        return $this->getStatus($copy) === CheckoutStatus::CheckedOut;
    }

    public function isAvailable(BookCopy $copy): bool {
        return $this->getStatus($copy) === CheckoutStatus::Available;
    }
}