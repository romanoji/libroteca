<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Application\Query\Pagination;

/**
 * @internal
 */
class MetadataTransformer
{
    /**
     * @param Metadata $metadata
     * @return array
     * @internal
     */
    public static function toArray(Metadata $metadata)
    {
        return [
            'page' => $metadata->page(),
            'per_page' => $metadata->perPage(),
            'results' => $metadata->results(),
            'pages' => $metadata->totalPages(),
            'total' => $metadata->totalCount()
        ];
    }
}
