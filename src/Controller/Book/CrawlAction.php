<?php

namespace App\Controller\Book;

use App\Import\BookMetadata\BookMetadataCrawler;
use App\Import\BookMetadata\CrawlException;
use App\Import\BookMetadata\InvalidIsbnException;
use App\Security\Voter\BookVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CrawlAction extends AbstractController {

    #[Route('/book/metadata/{isbn}/crawl', name: 'xhr_crawl_book')]
    public function __invoke(string $isbn, BookMetadataCrawler $crawler): JsonResponse {
        $this->denyAccessUnlessGranted(BookVoter::ADD);

        try {
            $metadata = $crawler->crawl($isbn);
            return new JsonResponse($metadata);
        } catch (InvalidIsbnException $exception) {
            return new JsonResponse([
                'error' => 'ISBN ungültig'
            ], Response::HTTP_BAD_REQUEST);
        } catch (CrawlException $exception) {
            return new JsonResponse([
                'error' => $exception->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}