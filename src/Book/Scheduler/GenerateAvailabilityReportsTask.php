<?php

namespace App\Book\Scheduler;

use App\Book\AvailabilityReportGenerator;
use App\Repository\BookRepositoryInterface;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Scheduler\Attribute\AsPeriodicTask;

#[AsPeriodicTask(frequency: AvailabilityReportGenerator::LIFETIME_IN_SECONDS, jitter: 10)]
readonly class GenerateAvailabilityReportsTask {
    public function __construct(private BookRepositoryInterface $bookRepository,
                                private MessageBusInterface $messageBus) {

    }

    /**
     * @throws ExceptionInterface
     */
    public function __invoke(): void {
        foreach($this->bookRepository->findAll() as $book) {
            $this->messageBus->dispatch(new RegenerateAvailabilityReport($book->getId()));
        }
    }
}