<?php

namespace App\Borrower\Scheduler;

use App\Borrower\BorrowerReportGenerator;
use App\Repository\BorrowerRepositoryInterface;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Scheduler\Attribute\AsPeriodicTask;

#[AsPeriodicTask(frequency: BorrowerReportGenerator::LIFETIME_IN_SECONDS, jitter: 10)]
readonly class GenerateBorrowerReportsTask {
    public function __construct(private BorrowerRepositoryInterface $borrowerRepository,
                                private MessageBusInterface $messageBus) { }

    /**
     * @throws ExceptionInterface
     */
    public function __invoke(): void {
        foreach($this->borrowerRepository->findAll() as $borrower) {
            $this->messageBus->dispatch(new GenerateBorrowerReport($borrower->getId()));
        }
    }
}