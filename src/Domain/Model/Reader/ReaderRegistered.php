<?php

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
     * ReaderRegisteredEvent constructor.
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
    public function readerID()
    {
        return $this->readerID;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function occuredOn()
    {
        return $this->occuredOn;
    }
}
