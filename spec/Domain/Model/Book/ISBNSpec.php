<?php

namespace spec\RJozwiak\Libroteca\Domain\Model\Book;

use RJozwiak\Libroteca\Domain\Model\Book\InvalidISBNException;
use RJozwiak\Libroteca\Domain\Model\Book\ISBN;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ISBNSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith('978-3-16-148410-0');
        $this->shouldHaveType(ISBN::class);
    }

    function it_throws_exception_when_isbn_has_incorrect_format()
    {
        $this->beConstructedWith('0-306');
        $this->shouldThrow(InvalidISBNException::class)->duringInstantiation();
    }

    function it_throws_exception_when_isbn_checksum_validation_fails()
    {
        $this->shouldThrow(InvalidISBNException::class)->during('__construct', ['0-306-40615-3']);
        $this->shouldThrow(InvalidISBNException::class)->during('__construct', ['978-3-16-148410-1']);
    }

    function it_returns_correct_format_of_isbn_10()
    {
        $this->beConstructedWith('0-306-40615-2');
        $this->format()->shouldReturn(ISBN::ISBN_10);
    }

    function it_returns_correct_format_of_isbn_13()
    {
        $this->beConstructedWith('978-3-16-148410-0');
        $this->format()->shouldReturn(ISBN::ISBN_13);
    }

    function it_returns_raw_isbn_10()
    {
        $this->beConstructedWith('3-928444-00-X');
        $this->isbn()->shouldReturn('392844400X');
    }

    function it_returns_raw_isbn_13()
    {
        $this->beConstructedWith('978-3-928444-00-2');
        $this->isbn()->shouldReturn('9783928444002');
    }

    function it_is_comparable(ISBN $sameISBN, ISBN $otherISBN)
    {
        $this->beConstructedWith('978-3-16-148410-0');

        $sameISBN->isbn()->willReturn('9783161484100');
        $this->equals($sameISBN)->shouldBe(true);

        $otherISBN->isbn()->willReturn('9783928444002');
        $this->equals($otherISBN)->shouldBe(false);
    }
}
