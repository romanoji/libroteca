<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Domain\Model\Book\ISBN;

abstract class ISBN
{
    /** @var null|string */
    private $isbn;

    /**
     * @param null|string $isbn
     * @throws \InvalidArgumentException
     */
    public function __construct(string $isbn = null)
    {
        $this->setISBN($isbn);
    }

    /**
     * @param null|string $isbn
     * @throws \InvalidArgumentException
     */
    private function setISBN(?string $isbn)
    {
        $this->validate($isbn);

        $this->isbn = $isbn;
    }

    /**
     * @param null|string $isbn
     * @throws \InvalidArgumentException
     */
    public function validate(?string $isbn)
    {
        $this->assertValidFormat($isbn);
        $this->assertValidChecksum($isbn);
    }

    /**
     * @param null|string $isbn
     * @throws \InvalidArgumentException
     */
    protected function assertValidFormat(?string $isbn)
    {
        if (!preg_match($this->format(), $isbn)) {
            throw new \InvalidArgumentException('Invalid isbn format.');
        }
    }

    /**
     * Regex ISBN format
     * @return string
     */
    abstract protected function format() : string;

    /**
     * @param string $isbn
     * @throws \InvalidArgumentException
     */
    protected function assertValidChecksum(?string $isbn)
    {
        $posBeforeChecksum = strlen($isbn) - 1;
        $checksumDigit = $this->checksumDigit($isbn);
        $isbnToCompare = substr($isbn, 0, $posBeforeChecksum).$checksumDigit;

        if ($isbn !== $isbnToCompare) {
            // it's intentionally, user shouldn't know about invalid checksum
            throw new \InvalidArgumentException('Invalid isbn format.');
        }
    }

    /**
     * @param string $isbn
     * @return string
     */
    abstract protected function checksumDigit(?string $isbn) : string;

    /**
     * @return null|string
     */
    public function isbn() : ?string
    {
        return $this->isbn;
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return (string) $this->isbn;
    }

    /**
     * @param ISBN $isbn
     * @return bool
     */
    public function equals(ISBN $isbn) : bool
    {
        // TODO: compare ISBN10 vs. ISBN13 using conversions
        return $this->isbn() === $isbn->isbn();
    }
}
