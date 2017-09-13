<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Domain\Model\Reader;

use RJozwiak\Libroteca\Domain\Model\Reader\Notification\NotificationType;
use RJozwiak\Libroteca\Domain\Model\Reader\Notification\Notifier;
use RJozwiak\Libroteca\Domain\Model\Reader\Notification\NotifierProvider;

class MembershipBasedNotifierProvider implements NotifierProvider
{
    /** @var Notifier */
    private $standardNotifier;

    /** @var Notifier */
    private $specialNotifier;

    /** @var \DateTimeImmutable */
    private $now;

    /**
     * @param Notifier $standardNotifier
     * @param Notifier $specialNotifier
     */
    public function __construct(
        Notifier $standardNotifier,
        Notifier $specialNotifier
    ) {
        $this->standardNotifier = $standardNotifier;
        $this->specialNotifier = $specialNotifier;
        $this->now = new \DateTimeImmutable();
    }

    /**
     * @param Reader $reader
     * @param NotificationType $notificationType
     * @return Notifier
     */
    public function provideFor(Reader $reader, NotificationType $notificationType): Notifier
    {
        if ($this->hasReaderLongMembership($reader)) {
            return $this->specialNotifier;
        }

        return $this->standardNotifier;
    }

    /**
     * @param Reader $reader
     * @return bool
     */
    protected function hasReaderLongMembership(Reader $reader): bool
    {
        $yearLater = $this->now->modify('+ 1 year');

        return $reader->createdAt() >= $yearLater;
    }

    /**
     * @param \DateTimeImmutable $now
     */
    public function setNowDateTime(\DateTimeImmutable $now)
    {
        $this->now = $now;
    }
}
