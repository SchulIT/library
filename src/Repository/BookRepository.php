<?php

namespace App\Repository;

use App\Entity\Book;
use App\Helper\IsbnHelper;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

class BookRepository extends AbstractRepository implements BookRepositoryInterface{

    public function __construct(EntityManagerInterface $em) {
        parent::__construct($em);
    }

    public function findOneById(int $id): ?Book {
        return $this->em->getRepository(Book::class)
            ->findOneBy(['id' => $id]);
    }

    public function find(int &$page, int &$limit, ?string $searchQuery = null): PaginatedResult {
        if($page < 1) {
            $page = 1;
        }

        if($limit < 1) {
            $limit = 25;
        }

        $qb = $this->em->createQueryBuilder()
            ->select(['b'])
            ->from(Book::class, 'b')
            ->orderBy('b.title', 'ASC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        if(!empty($searchQuery)) {
            $qb->where('b.title LIKE :searchQuery')
                ->orWhere('b.subtitle LIKE :searchQuery')
                ->orWhere('b.isbn LIKE :searchQuery')
                ->setParameter('searchQuery', '%' . $searchQuery . '%');
        }

        $query = $qb->getQuery();

        $paginator = new Paginator($query, fetchJoinCollection: true);

        return new PaginatedResult(
            iterator_to_array($paginator->getIterator()),
            $paginator->count()
        );
    }

    public function findAll(): array {
        return $this->em->getRepository(Book::class)->findBy(
            [],
            ['title' => 'asc']
        );
    }

    public function persist(Book $book): void {
        $this->em->persist($book);
        $this->em->flush();
    }

    public function remove(Book $book): void {
        $this->em->remove($book);
        $this->em->flush();
    }
}