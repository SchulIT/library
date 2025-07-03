<?php

namespace App\Import\Borrower;

use App\Entity\Borrower;
use App\Entity\BorrowerType;
use App\Repository\BookCopyRepositoryInterface;
use App\Repository\BorrowerRepositoryInterface;
use App\Repository\CheckoutRepositoryInterface;
use App\Utils\ArrayUtils;
use DateTime;
use Exception;
use League\Csv\Reader;

class CsvImporter {
    public const string IdHeader = 'ID';
    public const string FirstnameHeader = 'Vorname';
    public const string LastnameHeader = 'Nachname';
    public const string EmailHeader = 'E-Mail';
    public const string GradeHeader = 'Klasse';

    public function __construct(private readonly BorrowerRepositoryInterface $borrowerRepository, private readonly CheckoutRepositoryInterface $checkoutRepository, private readonly BookCopyRepositoryInterface $bookCopyRepository) {
    }

    public function importCsv(string $csvContent, BorrowerType $type, string $delimiter, bool $remove = false): void {
        $borrowers = ArrayUtils::createArrayWithKeys(
            $this->borrowerRepository->findByType($type),
            fn(Borrower $borrower) => $borrower->getBarcodeId()
        );

        $csv = Reader::createFromString($csvContent);
        $csv->setHeaderOffset(0);
        $csv->setDelimiter($delimiter);

        $toAdd = [ ];
        $toUpdate = [ ];
        $toRemove = [ ];

        $targetIds = [ ];

        foreach($csv->getRecords() as $offset => $record) {
            $id = $this->getColumnValue($record, self::IdHeader, true, 'Feld "ID" nicht vorhanden oder leer');
            $borrower = new Borrower();

            if(array_key_exists($id, $borrowers)) {
                $borrower = $borrowers[$id];
                $toUpdate[] = $borrower;
            } else {
                $borrower->setBarcodeId($id);
                $toAdd[] = $borrower;
            }

            $targetIds[] = $id;

            $firstname = $this->getColumnValue($record, self::FirstnameHeader, true, 'Feld "Vorname" nicht vorhanden oder leer');
            $lastname = $this->getColumnValue($record, self::LastnameHeader, true, 'Feld "Nachname" nicht vorhanden oder leer');
            $email = $this->getColumnValue($record, self::EmailHeader, true, 'Feld "E-Mail" nicht vorhanden oder leer');
            $grade = $this->getColumnValue($record, self::GradeHeader, false);

            $borrower->setType($type);
            $borrower->setFirstname($firstname);
            $borrower->setLastname($lastname);
            $borrower->setEmail($email);
            $borrower->setGrade($grade);
        }

        foreach($borrowers as $id => $borrower) {
            if(!in_array($id, $targetIds)) {
                $toRemove[] = $borrower;
            }
        }

        $this->borrowerRepository->beginTransaction();

        foreach($toAdd as $borrower) {
            $this->borrowerRepository->persist($borrower);
        }

        foreach($toUpdate as $borrower) {
            $this->borrowerRepository->persist($borrower);
        }

        /** @var Borrower $borrower */
        foreach($toRemove as $borrower) {
            foreach($borrower->getCheckouts() as $checkout) {
                $checkout->setEnd(new DateTime());
                $checkout->setComment('Ausleihe beendet, da Schüler gelöscht wurde');
                $this->checkoutRepository->persist($checkout);

                $copy = $checkout->getBookCopy();
                $copy->setCanCheckout(false);
                $copy->setComment(sprintf('Buch wurde vor dem Löschen des Schülers %s, %s (ID: %s) nicht zurückgegeben.', $borrower->getLastname(), $borrower->getFirstname(), $borrower->getBarcodeId()));
                $this->bookCopyRepository->persist($copy);
            }

            $this->borrowerRepository->remove($borrower);
        }

        $this->borrowerRepository->commit();
    }

    private function getColumnValue(array $record, string $key, bool $throw = true, string $throwMessage = 'Feld nicht gesetzt oder leer'): ?string {
        $value = $record[$key] ?? null;

        if($throw === true && empty($value)) {
            throw new CsvColumnException($throwMessage);
        }

        return $value;
    }
}