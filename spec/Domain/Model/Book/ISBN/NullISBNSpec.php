<?php

namespace spec\RJozwiak\Libroteca\Domain\Model\Book\ISBN;

use RJozwiak\Libroteca\Domain\Model\Book\ISBN\ISBN;
use RJozwiak\Libroteca\Domain\Model\Book\ISBN\ISBN10;
use RJozwiak\Libroteca\Domain\Model\Book\ISBN\ISBN13;
use RJozwiak\Libroteca\Domain\Model\Book\ISBN\NullISBN;
use PhpSpec\ObjectBehavior;

class NullISBNSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(NullISBN::class);
    }

    function it_is_subtype_of_isbn()
    {
        $this->shouldBeAnInstanceOf(ISBN::class);
    }

    function it_returns_null()
    {
        $this->isbn()->shouldReturn(null);
    }

    function it_is_comparable(NullISBN $sameISBN, ISBN10 $isbn10, ISBN13 $isbn13)
    {
        $sameISBN->isbn()->willReturn(null);
        $this->equals($sameISBN)->shouldBe(true);

        $isbn10->isbn()->willReturn('392844400X');
        $this->equals($isbn10)->shouldBe(false);
        $isbn13->isbn()->willReturn('9783928444002');
        $this->equals($isbn13)->shouldBe(false);
    }
}
