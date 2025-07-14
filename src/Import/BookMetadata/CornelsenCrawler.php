<?php

namespace App\Import\BookMetadata;

use App\Helper\IsbnHelper;
use Imagick;
use Psr\Log\LoggerInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

class CornelsenCrawler implements CrawlerInterface {

    private const string IsbnPrefix = '978-3-06';

    private const string UrlPattern = 'https://www.cornelsen.de/produkte/xxx-{isbn}';

    private const string PublisherName = 'Cornelsen';

    public function __construct(private readonly HttpClientInterface $client, private readonly IsbnHelper $isbnHelper, private readonly LoggerInterface $logger) {

    }

    public function getPriority(): int {
        return 1;
    }

    private function getUrlForIsbn(string $isbn): string {
        return str_replace('{isbn}', $this->isbnHelper->getCanonicalIsbn($isbn), self::UrlPattern);
    }

    public function supports(string $isbn): bool {
        if($this->isbnHelper->hasPrefix($isbn, self::IsbnPrefix) !== true) {
            return false;
        }

        try {
            $response = $this->client->request('GET', $this->getUrlForIsbn($isbn));

            return $response->getStatusCode() === Response::HTTP_OK;
        } catch (Throwable $e) {
            $this->logger->error(sprintf('[cornelsen] Fehler bei der Anfrage (%s)', $this->getUrlForIsbn($isbn)), [
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
            $dom = new Crawler($response->getContent());

            $metadata = new BookMetadata();
            $metadata->isbn = $isbn;
            $metadata->name = $dom->filter('main .cv-product__title-heading')->first()->text();
            $metadata->nameZusatz = $dom->filter('main .cv-product__title-subheading')->first()->text();
            $metadata->publisher = self::PublisherName;

            $this->crawlImage($dom, $metadata);

            return $metadata;
        } catch (Throwable $e) {
            $this->logger->error(sprintf('[cornelsen] Fehler der Abfrage der Produktinformationen (ISBN: %s)', $this->getUrlForIsbn($isbn)), [
                'exception' => $e
            ]);

            throw new CrawlException('Fehler bei der Abfrage der Produktinformationen', 0, $e);
        }
    }

    private function crawlImage(Crawler $dom, BookMetadata $metadata): void {
        try {
            $url = $dom->filter('main .cv-product-cover__image')->first()->attr('src');
            $response = $this->client->request('GET', $url);
            $imagick = new Imagick();
            $imagick->readImageFile($response->toStream());
            $imagick->setImageFormat('png');
            $metadata->image = base64_encode($imagick->getImageBlob());
        } catch (Throwable $e) {
            $this->logger->error(sprintf('[cornelsen] Fehler beim Download des Bildes (ISBN: %s)', $this->getUrlForIsbn($metadata->isbn)), [
                'exception' => $e
            ]);
        }
    }
}