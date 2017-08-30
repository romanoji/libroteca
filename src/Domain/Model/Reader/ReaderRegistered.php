<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Domain\Model\Reader;

use RJozwiak\Libroteca\Domain\Model\DomainEvent;

class ReaderRegistered implements DomainEvent
{
    // TODO:

    /** @var ReaderID */
    private $readerID;

    /** @var \DateTimeImmutable */
    private $occuredOn;

    /**
     * @param ReaderID $id
     */
    public function __construct(ReaderID $id)
    {
        $this->readerID = $id;
        $this->occuredOn = new \DateTimeImmutable();
    }

    /**
     * @return ReaderID
     */
    public function readerID(): ReaderID
    {
        return $this->readerID;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function occuredOn(): \DateTimeImmutable
    {
        return $this->occuredOn;
    }
}
