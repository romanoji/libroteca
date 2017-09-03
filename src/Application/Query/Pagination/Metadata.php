<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Application\Query\Pagination;

/**
 * @internal
 */
class Metadata
{
    /** @var int */
    private $page;

    /** @var int */
    private $perPage;

    /** @var int */
    private $results;

    /** @var int */
    private $totalPages;

    /** @var int */
    private $totalCount;

    /**
     * @param int $page
     * @param int $perPage
     * @param int $results
     * @param int $totalCount
     */
    public function __construct(
        int $page,
        int $perPage,
        int $results,
        int $totalCount
    ) {
        $this->page = $page;
        $this->perPage = $perPage;
        $this->results = $results;
        $this->totalCount = $totalCount;

        $this->calculateTotalPages();
    }

    private function calculateTotalPages()
    {
        $this->totalPages = (int) ceil($this->totalCount / $this->perPage);
    }

    /**
     * @return int
     */
    public function page(): int
    {
        return $this->page;
    }

    /**
     * @return int
     */
    public function perPage(): int
    {
        return $this->perPage;
    }

    /**
     * @return int
     */
    public function results(): int
    {
        return $this->results;
    }

    /**
     * @return int
     */
    public function totalPages(): int
    {
        return $this->totalPages;
    }

    /**
     * @return int
     */
    public function totalCount(): int
    {
        return $this->totalCount;
    }
}
