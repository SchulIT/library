<?php

namespace App\Label;

use App\Entity\BookCopy;
use Symfony\Component\Validator\Constraints as Assert;

class DownloadLabelsRequest {
    /**
     * @var BookCopy[]
     */
    #[Assert\Count(min: 1)]
    public array $copies = [ ];

    #[Assert\GreaterThanOrEqual(0)]
    public int $offset = 0;
}