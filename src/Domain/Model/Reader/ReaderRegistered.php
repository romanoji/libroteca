<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Domain\Model\Reader;

use RJozwiak\Libroteca\Domain\Event\DomainEvent;

class ReaderRegistered implements DomainEvent
{
    /** @var ReaderID */
    private $readerID;

    /** @var \DateTimeImmutable */
    private $occuredOn;

    /**
     * @param ReaderID $id
     * @param \DateTimeImmutable|null $occurredOn
     */
    public function __construct(ReaderID $id, \DateTimeImmutable $occurredOn = null)
    {
        $this->readerID = $id;
        $this->setOccurredOn($occurredOn);
    }

    /**
     * @param \DateTimeImmutable|null $occurredOn
     */
    private function setOccurredOn(\DateTimeImmutable $occurredOn = null)
    {
        if ($occurredOn === null) {
            $occurredOn = new \DateTimeImmutable();
        }

        $this->occuredOn = $occurredOn;
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
