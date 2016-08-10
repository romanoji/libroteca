<?php

namespace RJozwiak\Libroteca\Domain\Model\Book;

class ISBN
{
    const ISBN_10 = 'ISBN-10';
    const ISBN_13 = 'ISBN-13';

    const ISBN_10_FORMAT = '/^\d{9}[0-9X]$/';
    const ISBN_13_FORMAT = '/^\d{13}$/';

    /** @var string */
    private $isbn;

    /**
     * ISBN constructor.
     * @param string $isbn ISBN-10 or ISBN-13
     * @throws InvalidISBNException
     */
    public function __construct($isbn)
    {
        $this->setISBN(
            $this->rawISBN($isbn)
        );
    }

    /**
     * @param string $isbn
     * @return string
     */
    private function rawISBN($isbn)
    {
        return strtoupper(
            str_replace('-', '', $isbn)
        );
    }

    /**
     * @param string $isbn
     * @throws InvalidISBNException
     */
    private function setISBN($isbn)
    {
        $this->validate($isbn);
        $this->isbn = $isbn;
    }

    /**
     * @param $isbn
     * @throws InvalidISBNException
     */
    private function validate($isbn)
    {
        $this->assertValidFormat($isbn);
        $this->assertChecksum($isbn);
    }

    /**
     * @param string $isbn
     * @throws InvalidISBNException
     */
    private function assertValidFormat($isbn)
    {
        if (!preg_match(self::ISBN_10_FORMAT, $isbn) &&
            !preg_match(self::ISBN_13_FORMAT, $isbn)
        ) {
            throw InvalidISBNException::byFormat();
        }
    }

    /**
     * @param string $isbn
     * @throws InvalidISBNException
     */
    private function assertChecksum($isbn)
    {
        $posBeforeChecksum = strlen($isbn) - 1;
        $checksum = $this->checksum($isbn);
        $isbnToCompare = substr($isbn, 0, $posBeforeChecksum).$checksum;

        if ($isbn !== $isbnToCompare) {
            throw InvalidISBNException::byChecksum();
        }
    }

    /**
     * @param string $isbn
     * @throws InvalidISBNException
     * @return string
     */
    private function checksum($isbn)
    {
        $format = strlen($isbn) == 10 ? self::ISBN_10 : self::ISBN_13;

        switch ($format) {
            case self::ISBN_10:
                return $this->isbn10Checksum($isbn);
                break;
            case self::ISBN_13:
                return $this->isbn13Checksum($isbn);
                break;
            default:
                throw InvalidISBNException::byFormat();
                break;
        }
    }

    /**
     * @param string $isbn
     * @return string
     */
    private function isbn10Checksum($isbn)
    {
        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += $isbn[$i] * (10 - $i);
        }
        $controlNumber = 11 - ($sum % 11);

        return $controlNumber == 10 ? 'X' : (string) $controlNumber;
    }

    /**
     * @param string $isbn
     * @return string
     */
    private function isbn13Checksum($isbn)
    {
        $sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $sum += (($i % 2 == 0) ? 1 : 3) * $isbn[$i];
        }
        $controlNumber = 10 - ($sum % 10);

        return $controlNumber == 10 ? '0' : (string) $controlNumber;
    }

    /**
     * @return string
     */
    public function isbn()
    {
        return $this->isbn;
    }

    /**
     * @return string
     * @throws InvalidISBNException
     */
    public function format()
    {
        $length = strlen($this->isbn());

        if ($length == 10) {
            return self::ISBN_10;
        } elseif ($length == 13) {
            return self::ISBN_13;
        } else {
            throw InvalidISBNException::byFormat();
        }
    }

    /**
     * @param ISBN $isbn
     * @return bool
     */
    public function equals(ISBN $isbn)
    {
        // TODO: compare in both formats
        return $this->isbn() === $isbn->isbn();
    }
}
