<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Application\Command\ImportBooks;

class BookData
{
    /** @var string */
    private $isbn;

    /** @var string */
    private $title;

    /** @var array */
    private $authors;

    /** @var int */
    private $amount;

    /**
     * @param string $isbn
     * @param string $title
     * @param array $authors
     * @param int $amount
     */
    public function __construct(string $isbn, string $title, array $authors, int $amount)
    {
        $this->isbn = $isbn;
        $this->title = $title;
        $this->authors = $authors;
        $this->amount = $amount;
    }

    /**
     * @return string
     */
    public function isbn(): string
    {
        return $this->isbn;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->title;
    }

    /**
     * @return array
     */
    public function authors(): array
    {
        return $this->authors;
    }

    /**
     * @return int
     */
    public function amount(): int
    {
        return $this->amount;
    }
}
