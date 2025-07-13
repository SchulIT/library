<?php

namespace App\Book\Scheduler;

use App\Book\AvailabilityReportGenerator;
use App\Repository\BookRepositoryInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class RegenerateAvailabilityReportHandler {

    public function __construct(private AvailabilityReportGenerator $availabilityReportHelper,
                                private BookRepositoryInterface     $bookRepository) {

    }

    /**
     * @throws InvalidArgumentException
     */
    public function __invoke(RegenerateAvailabilityReport $message): void {
        $book = $this->bookRepository->findOneById($message->getBookId());

        if($book === null) {
            return;
        }

        $this->availabilityReportHelper->regenerateReportForBook($book);
    }
}