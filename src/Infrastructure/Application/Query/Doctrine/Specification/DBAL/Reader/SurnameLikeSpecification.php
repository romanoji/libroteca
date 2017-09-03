<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Application\Query\Doctrine\Specification\DBAL\Reader;

use RJozwiak\Libroteca\Application\Query\Specification\LikeSpecification;

class SurnameLikeSpecification extends LikeSpecification
{
    public function __construct(string $value)
    {
        parent::__construct('surname', $value);
    }
}
