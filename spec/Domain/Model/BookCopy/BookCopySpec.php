<?php

namespace spec\RJozwiak\Libroteca\Domain\Model\BookCopy;

use RJozwiak\Libroteca\Domain\Model\Book\BookID;
use RJozwiak\Libroteca\Domain\Model\BookCopy\BookCopy;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use RJozwiak\Libroteca\Domain\Model\BookCopy\BookCopyID;

class BookCopySpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(
            new BookCopyID(1),
            new BookID(1)
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(BookCopy::class);
    }

    function it_is_not_lent_to_anyone_by_default()
    {
        $this->isLent()->shouldBe(false);
        $this->readerID()->shouldBe(null);
    }

    function it_returns_identifier()
    {
        $this->id()->id()->shouldReturn(1);
    }

    function it_returns_book_identifier()
    {
        $this->bookID()->id()->shouldReturn(1);
    }

    // TODO:
}
