<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Application\Query\Pagination;

class Pagination
{
    public const RESULTS_LIMIT = 100;

    /** @var int */
    private $page;

    /** @var int */
    private $results;

    /**
     * @param int $page
     * @param int $results
     * @throws \InvalidArgumentException
     */
    public function __construct(int $page = 1, int $results = 10)
    {
        $this->setParams($page, $results);
    }

    /**
     * @param int $page
     * @param int $results
     * @throws \InvalidArgumentException
     */
    private function setParams(int $page, int $results)
    {
        if ($results > self::RESULTS_LIMIT) {
            throw new \InvalidArgumentException(
                sprintf("Pagination limit per page is %d results.", self::RESULTS_LIMIT)
            );
        }

        $this->page = $page;
        $this->results = $results;
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
    public function resultsCount(): int
    {
        return $this->results;
    }
}
