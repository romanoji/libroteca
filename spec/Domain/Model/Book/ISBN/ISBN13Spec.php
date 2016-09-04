<?php

namespace spec\RJozwiak\Libroteca\Domain\Model\Book\ISBN;

use RJozwiak\Libroteca\Domain\Model\Book\ISBN\ISBN;
use RJozwiak\Libroteca\Domain\Model\Book\ISBN\ISBN13;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ISBN13Spec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('9783928444002');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ISBN13::class);
    }

    function it_is_subtype_of_isbn()
    {
        $this->shouldBeAnInstanceOf(ISBN::class);
    }

    function it_throws_exception_on_invalid_format()
    {
        $this->beConstructedWith('9780');
        $this
            ->shouldThrow(new \InvalidArgumentException('Invalid isbn format.'))
            ->duringInstantiation();
    }

    function it_throws_exception_on_checksum_verification_fail()
    {
        $this->beConstructedWith('9783161484101');
        $this
            ->shouldThrow(new \InvalidArgumentException('Invalid isbn format.'))
            ->duringInstantiation();
    }

    function it_returns_isbn()
    {
        $this->isbn()->shouldReturn('9783928444002');
    }

    function it_is_comparable(ISBN13 $sameISBN, ISBN13 $otherISBN)
    {
        $sameISBN->isbn()->willReturn('9783928444002');
        $this->equals($sameISBN)->shouldBe(true);

        $otherISBN->isbn()->willReturn('9783161484100');
        $this->equals($otherISBN)->shouldBe(false);
    }
}
