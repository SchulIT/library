<?php

namespace App\Student\Selector;

use App\Entity\Borrower;
use App\Entity\BorrowerType;
use App\Repository\BorrowerRepositoryInterface;
use App\Sorting\Sorter;
use App\Utils\ArrayUtils;
use Symfony\Component\Serializer\SerializerInterface;

readonly class StudentSelectorJsonGenerator {

    public function __construct(private SerializerInterface $serializer, private BorrowerRepositoryInterface $borrowerRepository, private Sorter $sorter) {

    }

    public function generateJson(): string {
        return $this->serializer->serialize(
            $this->generateGrades(),
            'json'
        );
    }

    /**
     * @return Grade[]
     */
    public function generateGrades(): array {
        $students = $this->borrowerRepository->findByType(BorrowerType::Student);
        $studentsByGrade = ArrayUtils::createArrayWithKeys(
            array_filter($students, fn(Borrower $borrower) => !empty($borrower->getGrade())),
            fn(Borrower $borrower) => $borrower->getGrade(),
            true
        );

        $grades = [ ];

        foreach($studentsByGrade as $gradeName => $studentsInGrade) {
            $studentsInGrade = array_map(
                fn(Borrower $borrower) => new Student($borrower->getId(), $borrower->getFirstName(), $borrower->getLastName()),
                $studentsInGrade
            );
            $this->sorter->sort($studentsInGrade, StudentSortStrategy::class);
            $grade = new Grade($gradeName, $studentsInGrade);

            $grades[] = $grade;
        }

        $this->sorter->sort($grades, GradeSortStrategy::class);

        return $grades;
    }
}