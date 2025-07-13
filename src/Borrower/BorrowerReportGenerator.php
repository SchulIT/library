<?php

namespace App\Borrower;

use App\Entity\Borrower;
use Psr\Cache\InvalidArgumentException;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class BorrowerReportGenerator {
    private const string KEY_PATTERN = 'borrower.report.%d';
    public const int LIFETIME_IN_SECONDS = 3600;

    public function __construct(private readonly CacheInterface $cache) {

    }

    /**
     * Enforces regeneration of the report.
     *
     * @throws InvalidArgumentException
     */
    public function regenerateReportForBorrower(Borrower $borrower): BorrowerReport {
        $this->cache->delete(sprintf(self::KEY_PATTERN, $borrower->getId()));
        return $this->generateReportForBorrower($borrower);
    }


    /**
     * @throws InvalidArgumentException
     */
    public function generateReportForBorrower(Borrower $borrower): BorrowerReport {
        return $this->cache->get(sprintf(self::KEY_PATTERN, $borrower->getId()), function(ItemInterface $item) use($borrower): BorrowerReport {
            $item->expiresAfter(self::LIFETIME_IN_SECONDS);

            $checkouts = $borrower->getCheckouts();
            $total = count($checkouts);
            $active = 0;

            foreach($checkouts as $checkout) {
                if($checkout->getEnd() === null) {
                    $active++;
                }
            }

            return new BorrowerReport($total, $active);
        });
    }
}