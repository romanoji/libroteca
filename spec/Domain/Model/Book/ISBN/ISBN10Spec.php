<?php

namespace spec\RJozwiak\Libroteca\Domain\Model\Book\ISBN;

use RJozwiak\Libroteca\Domain\Model\Book\ISBN\ISBN;
use RJozwiak\Libroteca\Domain\Model\Book\ISBN\ISBN10;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ISBN10Spec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('392844400X');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ISBN10::class);
        $this->shouldBeAnInstanceOf(ISBN::class);
    }

    function it_throws_exception_on_invalid_format()
    {
        $this->beConstructedWith('0306');
        $this
            ->shouldThrow(new \InvalidArgumentException('Invalid isbn format.'))
            ->duringInstantiation();
    }

    function it_throws_exception_on_checksum_verification_fail()
    {
        $this->beConstructedWith('0306406153');
        $this
            ->shouldThrow(new \InvalidArgumentException('Invalid isbn checksum.'))
            ->duringInstantiation();
    }

    function it_returns_isbn_string()
    {
        $this->__toString()->shouldReturn('392844400X');
    }

    function it_is_comparable(ISBN10 $sameISBN, ISBN10 $otherISBN)
    {
        $sameISBN->isbn()->willReturn('392844400X');
        $this->equals($sameISBN)->shouldBe(true);

        $otherISBN->isbn()->willReturn('0306406152');
        $this->equals($otherISBN)->shouldBe(false);
    }
}
