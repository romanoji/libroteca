<?php

namespace spec\RJozwiak\Libroteca\Domain\Model\Reader;

use RJozwiak\Libroteca\Domain\Model\AggregateRoot;
use RJozwiak\Libroteca\Domain\Model\Reader\Email;
use RJozwiak\Libroteca\Domain\Model\Reader\Name;
use RJozwiak\Libroteca\Domain\Model\Reader\Phone;
use RJozwiak\Libroteca\Domain\Model\Reader\Reader;
use PhpSpec\ObjectBehavior;
use RJozwiak\Libroteca\Domain\Model\Reader\ReaderID;
use RJozwiak\Libroteca\Domain\Model\Reader\Surname;

class ReaderSpec extends ObjectBehavior
{
    function let(ReaderID $readerID)
    {
        $readerID->id()->willReturn(1337);

        $this->beConstructedWith(
            $readerID,
            new Name('John'),
            new Surname('Kowalsky'),
            new Email('john.kowalsky@mail.com'),
            new Phone('+48 123456789')
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Reader::class);
    }

    function it_is_aggregate_root()
    {
        $this->shouldBeAnInstanceOf(AggregateRoot::class);
    }

    function it_returns_identifier()
    {
        $this->id()->id()->shouldReturn(1337);
    }

    function it_returns_name()
    {
        $this->name()->shouldBeLike(new Name('John'));
    }

    function it_returns_surname()
    {
        $this->surname()->shouldBeLike(new Surname('Kowalsky'));
    }

    function it_returns_email()
    {
        $this->email()->shouldBeLike(new Email('john.kowalsky@mail.com'));
    }

    function it_returns_phone()
    {
        $this->phone()->shouldBeLike(new Phone('+48 123456789'));
    }
}
