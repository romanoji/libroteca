<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Domain\Model\Book\ISBN;

class ISBNFactory
{
    /**
     * @param null|string $isbn
     * @return ISBN
     * @throws \InvalidArgumentException
     */
    public function create(string $isbn = null): ISBN
    {
        if ($isbn === null || $isbn === '') {
            return new NullISBN();
        }

        $isbn = $this->toRawISBN($isbn);
        switch ($isbn) {
            case strlen($isbn) === 10:
                return new ISBN10($isbn);
            case strlen($isbn) === 13:
                return new ISBN13($isbn);
            default:
                throw new \InvalidArgumentException('Invalid isbn length.');
        }
    }

    /**
     * @param string $isbn
     * @return string
     */
    private function toRawISBN(string $isbn): string
    {
        return strtoupper(
            str_replace('-', '', $isbn)
        );
    }
}
