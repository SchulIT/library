<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\BookCopy;
use Doctrine\ORM\Tools\Pagination\Paginator;

class BookCopyRepository extends AbstractTransactionalRepository implements BookCopyRepositoryInterface {

    public function findById(int $id): ?BookCopy {
        return $this->em->getRepository(BookCopy::class)->findOneBy(['id' => $id]);
    }

    public function findAll(): array {
        return $this->em->getRepository(BookCopy::class)->findAll();
    }

    public function findByBook(Book $book): array {
        return $this->em->getRepository(BookCopy::class)
            ->findBy(
                [
                    'book' => $book
                ],
                [
                    'createdAt' => 'ASC'
                ]
            );
    }

    public function findAllByIds(array $ids): array {
        return $this->em->createQueryBuilder()
            ->select('c')
            ->from(BookCopy::class, 'c')
            ->where('c.id IN (:ids)')
            ->setParameter('ids', $ids)
            ->getQuery()
            ->getResult();
    }

    public function findByBookPaginated(Book $book, int &$page, int &$limit): PaginatedResult {
        if($page < 1) {
            $page = 1;
        }

        if($limit < 1 || $limit > 500) {
            $limit = 25;
        }

        $query = $this->em->createQueryBuilder()
            ->select(['c', 'b'])
            ->from(BookCopy::class, 'c')
            ->leftJoin('c.book', 'b')
            ->orderBy('c.createdAt', 'ASC')
            ->addOrderBy('c.id', 'ASC')
            ->where('c.book = :book')
            ->setParameter('book', $book)
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery();

        $paginator = new Paginator($query, fetchJoinCollection: true);

        return new PaginatedResult(
            iterator_to_array($paginator),
            $paginator->count()
        );
    }

    public function persist(BookCopy $copy): void {
        $this->em->persist($copy);
        $this->flushIfNotInTransaction();
    }

    public function remove(BookCopy $copy): void {
        $this->em->remove($copy);
        $this->flushIfNotInTransaction();
    }

    public function countNotAvailableByBook(Book $book): int {
        return $this->em->createQueryBuilder()
            ->select('COUNT(b)')
            ->from(BookCopy::class, 'b')
            ->where('b.book = :book')
            ->andWhere('b.canCheckout = false')
            ->setParameter('book', $book)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countAll(): int {
        return $this->em->createQueryBuilder()
            ->select('COUNT(1)')
            ->from(BookCopy::class, 'c')
            ->getQuery()
            ->getSingleScalarResult();
    }
}