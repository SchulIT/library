<?php

namespace App\Tests\Helper;

use App\Helper\IsbnHelper;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;

class IsbnHelperTest extends TestCase {
    public function testGetHyphenatedIsbn13() {
        $validator = Validation::createValidator();
        $isbnHelper = new IsbnHelper($validator);
        $this->assertEquals('978-3-12-733851-5', $isbnHelper->getHyphenatedIsbn13('9783127338515', '978-3-12'));
    }

    public function testConvertToIsbn13() {
        $validator = Validation::createValidator();
        $isbnHelper = new IsbnHelper($validator);

        $this->assertEquals('9781861972712', $isbnHelper->convertIsbn10To13('1861972717'));
        $this->assertEquals('9781861972712', $isbnHelper->convertIsbn10To13('186-197271-7'));
    }
}