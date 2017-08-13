<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Domain\Model\Book\ISBN\Exception;

use RJozwiak\Libroteca\Domain\Model\DomainLogicException;

class ISBNAlreadyInUseException extends DomainLogicException
{
}
