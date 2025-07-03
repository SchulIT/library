<?php

namespace App\BookCopy;

use App\Entity\BookCopy;
use App\Repository\BookCopyRepositoryInterface;

readonly class BookCopyCreator {

    public function __construct(private BookCopyRepositoryInterface $bookCopyRepository) {

    }

    /**
     * @param BookCopyCreateRequest $request
     * @return BookCopy[]
     */
    public function createCopies(BookCopyCreateRequest $request): array {
        $this->bookCopyRepository->beginTransaction();
        $copies = [];

        for($i = 0; $i < $request->count; $i++) {
            $copy = (new BookCopy())
                ->setBook($request->book)
                ->setCanCheckout(true);

            $this->bookCopyRepository->persist($copy);
            $copies[] = $copy;
        }

        $this->bookCopyRepository->commit();

        return $copies;
    }
}