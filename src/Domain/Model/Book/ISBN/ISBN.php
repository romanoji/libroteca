<?php

namespace RJozwiak\Libroteca\Domain\Model\Book\ISBN;

abstract class ISBN
{
    /** @var string */
    private $isbn;

    /**
     * ISBN constructor.
     * @param string $isbn
     * @throws \InvalidArgumentException
     */
    public function __construct($isbn)
    {
        $this->setISBN($isbn);
    }

    /**
     * @param string $isbn
     * @throws \InvalidArgumentException
     */
    private function setISBN($isbn)
    {
        $this->validate($isbn);

        $this->isbn = $isbn;
    }

    /**
     * @param string $isbn
     * @throws \InvalidArgumentException
     */
    public function validate($isbn)
    {
        $this->assertValidFormat($isbn);
        $this->assertValidChecksum($isbn);
    }

    /**
     * @param string $isbn
     * @throws \InvalidArgumentException
     */
    public function assertValidFormat($isbn)
    {
        if (!preg_match($this->format(), $isbn)) {
            throw new \InvalidArgumentException('Invalid isbn format.');
        }
    }

    /**
     * Regex ISBN format
     * @return string
     */
    abstract protected function format();

    /**
     * @param string $isbn
     * @throws \InvalidArgumentException
     */
    public function assertValidChecksum($isbn)
    {
        $posBeforeChecksum = strlen($isbn) - 1;
        $checksumDigit = $this->checksumDigit($isbn);
        $isbnToCompare = substr($isbn, 0, $posBeforeChecksum).$checksumDigit;

        if ($isbn !== $isbnToCompare) {
            throw new \InvalidArgumentException('Invalid isbn checksum.');
        }
    }

    /**
     * @param string $isbn
     * @return string
     */
    abstract protected function checksumDigit($isbn);

    /**
     * @return string
     */
    public function isbn()
    {
        return $this->isbn;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->isbn;
    }

    /**
     * @param ISBN $isbn
     * @return bool
     */
    public function equals(ISBN $isbn)
    {
        return $this->isbn() === $isbn->isbn();
    }
}
