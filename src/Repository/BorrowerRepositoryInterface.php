<?php

namespace App\Repository;

use App\Entity\Borrower;
use App\Entity\BorrowerType;

interface BorrowerRepositoryInterface extends TransactionalRepositoryInterface {

    public function findOneById(int $id): ?Borrower;

    public function findByExternalId(string $externalId): ?Borrower;

    /**
     * @param Borrower[] $types
     * @param string|null $grade
     * @param int $page
     * @param int $limit
     * @param string|null $searchQuery
     * @param bool $onlyWithActiveCheckouts
     * @return PaginatedResult<Borrower>
     */
    public function find(array $types, ?string $grade, int &$page, int &$limit, ?string $searchQuery = null, bool $onlyWithActiveCheckouts = false): PaginatedResult;

    /**
     * @param BorrowerType $type
     * @return Borrower[]
     */
    public function findByType(BorrowerType $type): array;

    /**
     * @return string[]
     */
    public function findGrades(): array;

    /**
     * @return Borrower[]
     */
    public function findAll(): array;

    /**
     * @param string[] $emailsOrExternalIds
     * @return Borrower[]
     */
    public function findAllByEmailOrExternalId(array $emailsOrExternalIds): array;

    public function countAll(): int;

    public function persist(Borrower $person): void;

    public function remove(Borrower $person): void;
}