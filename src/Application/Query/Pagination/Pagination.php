<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Application\Query\Pagination;

class Pagination
{
    public const RESULTS_LIMIT = 100;

    /** @var int */
    private $page;

    /** @var int */
    private $perPage;

    /**
     * @param int $page
     * @param int $perPage
     * @throws \InvalidArgumentException
     */
    public function __construct(int $page = 1, int $perPage = 10)
    {
        $this->setParams($page, $perPage);
    }

    /**
     * @param int $page
     * @param int $perPage
     * @throws \InvalidArgumentException
     */
    private function setParams(int $page, int $perPage)
    {
        if ($perPage > self::RESULTS_LIMIT) {
            throw new \InvalidArgumentException(
                sprintf("Pagination per page limit is %d results.", self::RESULTS_LIMIT)
            );
        }

        $this->page = $page;
        $this->perPage = $perPage;
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
}
