<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Domain\Model\Book\Exception;

use RJozwiak\Libroteca\Domain\Model\AggregateNotFoundException;

class BookNotFoundException extends AggregateNotFoundException
{
}
