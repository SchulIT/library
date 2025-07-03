<?php

namespace App\BookCopy;

use App\Entity\Book;
use Symfony\Component\Validator\Constraints as Assert;

class BookCopyCreateRequest {
    #[Assert\NotNull]
    public Book $book;

    #[Assert\GreaterThan(0)]
    public int $count;
}