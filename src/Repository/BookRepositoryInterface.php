<?php

namespace App\Repository;

use App\Entity\Book;

interface BookRepositoryInterface {

    public function findOneById(int $id): ?Book;

    /**
     * @param int $page
     * @param int $limit
     * @param string|null $searchQuery
     * @return PaginatedResult<Book>
     */
    public function find(int &$page, int &$limit, ?string $searchQuery = null): PaginatedResult;

    /**
     * @return Book[]
     */
    public function findAll(): array;

    public function persist(Book $book): void;

    public function remove(Book $book): void;
}