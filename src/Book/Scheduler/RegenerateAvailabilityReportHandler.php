<?php

namespace App\Book\Scheduler;

use App\Book\AvailabilityReportHelper;
use App\Repository\BookRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class RegenerateAvailabilityReportHandler {

    public function __construct(private AvailabilityReportHelper $availabilityReportHelper,
                                private BookRepositoryInterface $bookRepository) {

    }

    public function __invoke(RegenerateAvailabilityReport $message): void {
        $book = $this->bookRepository->findOneById($message->getBookId());

        if($book === null) {
            return;
        }

        $this->availabilityReportHelper->regenerateReportForBook($book);
    }
}