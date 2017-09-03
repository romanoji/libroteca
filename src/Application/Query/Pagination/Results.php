<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Application\Query\Pagination;

class Results
{
    /** @var array */
    private $data;

    /** @var Metadata */
    private $metadata;

    /**
     * @param array $data
     * @param Metadata $metadata
     */
    public function __construct(array $data, Metadata $metadata)
    {
        $this->data = $data;
        $this->metadata = $metadata;
    }

    /**
     * @return array
     */
    public function data(): array
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function metadata(): array
    {
        return MetadataTransformer::toArray($this->metadata);
    }
}
