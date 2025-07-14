<?php

namespace App\Repository;

use App\Entity\Borrower;
use App\Entity\BorrowerType;
use Doctrine\ORM\Tools\Pagination\Paginator;

class BorrowerRepository extends AbstractTransactionalRepository implements BorrowerRepositoryInterface {

    public function findOneById(int $id): ?Borrower {
        return $this->em->getRepository(Borrower::class)
            ->findOneBy([
                'id' => $id
            ]);
    }

    public function findByExternalId(string $externalId): ?Borrower {
        return $this->em->getRepository(Borrower::class)
            ->findOneBy([
                'externalId' => $externalId
            ]);
    }

    public function find(array $types, ?string $grade, int &$page, int &$limit, ?string $searchQuery = null, bool $onlyWithActiveCheckouts = false): PaginatedResult {
        if($page < 1) {
            $page = 1;
        }

        if($limit < 1) {
            $limit = 25;
        }

        $qb = $this->em->createQueryBuilder()
            ->select(['p'])
            ->from(Borrower::class, 'p')
            ->where('p.type IN (:types)')
            ->setParameter('types', $types)
            ->orderBy('p.lastname', 'asc')
            ->addOrderBy('p.firstname', 'asc')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        if($grade !== null) {
            $qb->andWhere('p.grade = :grade')->setParameter('grade', $grade);
        }

        if($onlyWithActiveCheckouts === true) {
            $qbInner = $this->em->createQueryBuilder()
                ->select('bInner.id')
                ->from(Borrower::class, 'bInner')
                ->innerJoin('bInner.checkouts', 'cInner')
                ->where('cInner.end IS NULL');

            $qb->andWhere(
                $qb->expr()->in('p.id', $qbInner->getDQL())
            );
        }

        if(!empty($searchQuery)) {
            $qb->andWhere(
                $qb->expr()->orX(
                    'p.barcodeId = :searchQuery',
                    'p.firstname LIKE :searchQuery',
                    'p.lastname LIKE :searchQuery',
                    'p.email LIKE :searchQuery'
                )
            )
                ->setParameter('searchQuery', '%'.$searchQuery.'%');
        }

        $query = $qb->getQuery();

        $paginator = new Paginator($query, fetchJoinCollection: true);

        return new PaginatedResult(
            iterator_to_array($paginator->getIterator()),
            $paginator->count()
        );
    }

    public function findAll(): array {
        return $this->em->getRepository(Borrower::class)
            ->findBy(
                [],
                [
                    'lastname' => 'asc',
                    'firstname' => 'asc'
                ]
            );
    }

    public function findByType(BorrowerType $type): array {
        return $this->em->getRepository(Borrower::class)
            ->findBy(
                [
                    'type' => $type
                ],
                [
                    'lastname' => 'asc',
                    'firstname' => 'asc'
                ]
            );
    }

    public function findGrades(): array {
        $qb = $this->em->createQueryBuilder()
            ->select('DISTINCT p.grade')
            ->from(Borrower::class, 'p')
            ->where('p.grade IS NOT NULL')
            ->orderBy('p.grade', 'asc');

        return array_column($qb->getQuery()->getScalarResult(), 'grade');
    }

    public function countAll(): int {
        return $this->em->createQueryBuilder()
            ->select('COUNT(1)')
            ->from(Borrower::class, 'p')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function persist(Borrower $person): void {
        $this->em->persist($person);
        $this->flushIfNotInTransaction();
    }

    public function remove(Borrower $person): void {
        $this->em->remove($person);
        $this->flushIfNotInTransaction();
    }

    public function findAllByEmailOrExternalId(array $emailsOrExternalIds): array {
        return $this->em->createQueryBuilder()
            ->select('b')
            ->from(Borrower::class, 'b')
            ->where('b.email IN (:emailsOrExternalIds)')
            ->orWhere('b.barcodeId IN (:emailsOrExternalIds)')
            ->setParameter('emailsOrExternalIds', $emailsOrExternalIds)
            ->getQuery()
            ->getResult();
    }
}