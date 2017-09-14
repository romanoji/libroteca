<?php

namespace spec\RJozwiak\Libroteca\Domain\Model\Reader;

use RJozwiak\Libroteca\Domain\Event\DomainEvent;
use RJozwiak\Libroteca\Domain\Model\Reader\ReaderID;
use RJozwiak\Libroteca\Domain\Model\Reader\ReaderRegistered;
use PhpSpec\ObjectBehavior;

class ReaderRegisteredSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(new ReaderID(1337));
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ReaderRegistered::class);
    }

    function it_is_event()
    {
        $this->shouldImplement(DomainEvent::class);
    }

    function it_refers_to_reader_by_identifier()
    {
        $this->readerID()->id()->shouldReturn(1337);
    }

    function it_returns_time_of_occurence()
    {
        $this->occuredOn()->shouldReturnAnInstanceOf(\DateTimeImmutable::class);
    }
}
