<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Application\Query\Doctrine\Specification\ORM\Book;

use RJozwiak\Libroteca\Application\Query\Specification\LikeSpecification;

class ISBNLikeSpecification extends LikeSpecification
{
    public function __construct(string $value)
    {
        parent::__construct('t.isbn', $value);
    }
}
