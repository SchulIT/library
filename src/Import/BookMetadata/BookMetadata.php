<?php

namespace App\Import\BookMetadata;

class BookMetadata {
    public string $isbn;

    public string|null $name = null;

    public string|null $nameZusatz = null;

    public string|null $klasse = null;

    public string|null $publisher = null;

    public string|null $image = null;
}