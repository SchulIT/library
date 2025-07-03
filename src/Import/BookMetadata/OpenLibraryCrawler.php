<?php

namespace App\Import\BookMetadata;

use Imagick;
use Psr\Log\LoggerInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

class OpenLibraryCrawler implements CrawlerInterface {

    private const string UrlPattern = 'https://openlibrary.org/api/books?bibkeys=ISBN:{isbn}&jscmd=details&format=json';
    private const string ImagePattern = 'https://covers.openlibrary.org/b/isbn/{isbn}-L.jpg';

    public function __construct(private readonly HttpClientInterface $client, private readonly LoggerInterface $logger) {

    }

    public function getPriority(): int {
        return 10000;
    }

    private function getUrlForIsbn(string $isbn): string {
        return str_replace('{isbn}', $isbn, self::UrlPattern);
    }

    private function getImageUrlForIsbn(string $isbn): string {
        return str_replace('{isbn}', $isbn, self::ImagePattern);
    }

    public function supports(string $isbn): bool {
        try {
            $response = $this->client->request('GET', $this->getUrlForIsbn($isbn));

            return $response->getStatusCode() === Response::HTTP_OK && !empty(trim($response->getContent()));
        } catch (Throwable $e) {
            $this->logger->error(sprintf('[openlibrary] Fehler bei der Anfrage (%s)', $this->getUrlForIsbn($isbn)), [
                'exception' => $e
            ]);
            return false;
        }
    }

    public function crawl(string $isbn): BookMetadata {
        try {
            $response = $this->client->request('GET', $this->getUrlForIsbn($isbn));
            $json = json_decode($response->getContent(), true);

            $metadata = new BookMetadata();
            $metadata->isbn = $isbn;

            $key = "ISBN:" . $isbn;
            $metadata->name = $json[$key]['details']['title'];
            $metadata->publisher = $json[$key]['details']['publishers'][0];

            $this->crawlImage($isbn, $metadata);

            return $metadata;
        } catch (Throwable $e) {
            $this->logger->error(sprintf('[openlibrary] Fehler der Abfrage der Produktinformationen (ISBN: %s)', $this->getUrlForIsbn($metadata->isbn)), [
                'exception' => $e
            ]);

            throw new CrawlException('Fehler bei der Abfrage der Produktinformationen', 0, $e);
        }
    }

    private function crawlImage(string $isbn, BookMetadata $metadata): void {
        try {
            $response = $this->client->request('GET', $this->getImageUrlForIsbn($isbn));
            $imagick = new Imagick();
            $imagick->readImageFile($response->toStream());
            $imagick->setImageFormat('png');
            $metadata->image = base64_encode($imagick->getImageBlob());
        } catch (Throwable $e) {
            $this->logger->error(sprintf('[openlibrary] Fehler beim Download des Bildes (ISBN: %s)', $this->getUrlForIsbn($metadata->isbn)), [
                'exception' => $e
            ]);
        }
    }
}