<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Persistence\Doctrine\Type;

use RJozwiak\Libroteca\Domain\Model\Book\Author;

class AuthorsType extends TypedTextArray
{
    /**
     * @return string
     */
    protected function typeClass(): string
    {
        return Author::class;
    }

    /**
     * @return string
     */
    protected function valueMethod(): string
    {
        return 'name';
    }
}
