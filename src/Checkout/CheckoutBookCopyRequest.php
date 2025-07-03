<?php

namespace App\Checkout;

use App\Entity\BookCopy;
use App\Entity\Borrower;
use Symfony\Component\Validator\Constraints as Assert;

class CheckoutBookCopyRequest {
    #[Assert\NotNull]
    public BookCopy $bookCopy;

    #[Assert\NotNull]
    public Borrower $borrower;
}