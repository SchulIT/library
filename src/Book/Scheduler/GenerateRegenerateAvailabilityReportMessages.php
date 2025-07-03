<?php

namespace App\Book\Scheduler;

use App\Book\AvailabilityReportHelper;
use App\Repository\BookRepositoryInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Scheduler\Attribute\AsPeriodicTask;

#[AsPeriodicTask(frequency: AvailabilityReportHelper::LIFETIME_IN_SECONDS, jitter: 10)]
readonly class GenerateRegenerateAvailabilityReportMessages {
    public function __construct(private BookRepositoryInterface $bookRepository,
                                private MessageBusInterface     $messageBus) {

    }

    public function __invoke(): void {
        foreach($this->bookRepository->findAll() as $book) {
            $this->messageBus->dispatch(new RegenerateAvailabilityReport($book->getId()));
        }
    }
}