<?php

namespace App\Import\BookMetadata;

use App\Helper\IsbnHelper;
use Imagick;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

class WestermannCrawler implements CrawlerInterface {

    private const array IsbnPrefixes = [
        '978-3-14' => 'Westermann',
        '978-3-507' => 'Schroedel'
    ];

    private const string UrlPattern = 'https://www.westermann.de/artikel/{isbn}';
    private const string ImagePattern = 'https://c.wgr.de/i/artikel/288x288-fit/{isbn}.png';

    public function __construct(private readonly HttpClientInterface $client, private readonly IsbnHelper $isbnHelper, private readonly LoggerInterface $logger) {

    }

    public function getPriority(): int {
        return 1;
    }

    private function getUrlForIsbn(string $isbn): string {
        return str_replace('{isbn}', $this->isbnHelper->getHyphenatedIsbn13($isbn, ...array_keys(self::IsbnPrefixes)), self::UrlPattern);
    }

    private function hasPrefix(string $isbn): bool {
        foreach(self::IsbnPrefixes as $isbnPrefix => $publisher) {
            if($this->isbnHelper->hasPrefix($isbn, $isbnPrefix) === true) {
                return true;
            }
        }

        return false;
    }

    public function supports(string $isbn): bool {
        if($this->hasPrefix($isbn) !== true) {
            return false;
        }

        try {
            $response = $this->client->request('GET', $this->getUrlForIsbn($isbn));

            return $response->getStatusCode() === Response::HTTP_OK;
        } catch (Throwable $e) {
            $this->logger->error(sprintf('[westermann] Fehler bei der Anfrage (%s)', $this->getUrlForIsbn($isbn)), [
                'exception' => $e
            ]);
            return false;
        }
    }

    /**
     * @throws CrawlException
     */
    public function crawl(string $isbn): BookMetadata {
        try {
            $response = $this->client->request('GET', $this->getUrlForIsbn($isbn));
            $dom = new Crawler($response->getContent(true));

            $metadata = new BookMetadata();
            $metadata->isbn = $isbn;
            try {
                $metadata->name = $dom->filter('.productdetail h1')->first()->text();
            } catch (InvalidArgumentException) { }
            try {
                $metadata->nameZusatz = $dom->filter('.productdetail h2')->first()->text();
            } catch (InvalidArgumentException) { }
            try {
                $metadata->klasse = $dom->filter('.klassenstufe-bereich')->first()->text();
            } catch (InvalidArgumentException) { }
            $metadata->publisher = $this->getPublisher($isbn);

            $this->crawlImage($isbn, $metadata);

            return $metadata;
        } catch (Throwable $e) {
            $this->logger->error(sprintf('[westermann] Fehler der Abfrage der Produktinformationen (ISBN: %s)', $this->getUrlForIsbn($metadata->isbn)), [
                'exception' => $e
            ]);

            throw new CrawlException('Fehler bei der Abfrage der Produktinformationen', 0, $e);
        }
    }

    private function crawlImage(string $isbn, BookMetadata $metadata): void {
        try {
            $response = $this->client->request('GET', str_replace('{isbn}', $this->isbnHelper->getHyphenatedIsbn13($isbn, ...array_keys(self::IsbnPrefixes)), self::ImagePattern));
            $imagick = new Imagick();
            $imagick->readImageFile($response->toStream());
            $imagick->setImageFormat('png');
            $metadata->image = base64_encode($imagick->getImageBlob());
        } catch (Throwable $e) {
            $this->logger->error(sprintf('[westermann] Fehler beim Download des Bildes (ISBN: %s)', $this->getUrlForIsbn($metadata->isbn)), [
                'exception' => $e
            ]);
        }
    }

    private function getPublisher(string $isbn): ?string {
        foreach(self::IsbnPrefixes as $isbnPrefix => $publisher) {
            if($this->isbnHelper->hasPrefix($isbn, $isbnPrefix) === true) {
                return $publisher;
            }
        }

        return null;
    }
}