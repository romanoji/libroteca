<?php

namespace spec\RJozwiak\Libroteca\Domain\Model\Book;

use RJozwiak\Libroteca\Domain\Model\Book\Author;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use RJozwiak\Libroteca\Domain\Model\Book\InvalidAuthorException;

class AuthorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith('Joanne Kathleen', 'Rowling');
        $this->shouldHaveType(Author::class);
    }

    function it_throws_exception_on_empty_name()
    {
        $this->beConstructedWith('', 'Rowling');
        $this->shouldThrow(InvalidAuthorException::class)->duringInstantiation();
    }

    function it_throws_exception_when_name_length_is_beyond_range()
    {
        $this->beConstructedWith('Peaches Honeyblossom Michelle Charlotte Angel Vanessa', 'Geldof');
        $this->shouldThrow(InvalidAuthorException::class)->duringInstantiation();
    }

    function it_throws_exception_when_name_has_invalid_format()
    {
        $this->beConstructedWith('Anthon1', 'Veraas');
        $this->shouldThrow(InvalidAuthorException::class)->duringInstantiation();
    }

    function it_throws_exception_on_empty_surname()
    {
        $this->beConstructedWith('George R.R.', '');
        $this->shouldThrow(InvalidAuthorException::class)->duringInstantiation();
    }

    function it_throws_exception_when_surname_length_is_beyond_range()
    {
        $this->beConstructedWith('Hubert Blaine', 'Wolfeschlegelsteinhausenbergerdorffwelchevoralternwaren');
        $this->shouldThrow(InvalidAuthorException::class)->duringInstantiation();
    }

    function it_throws_exception_when_surname_has_invalid_format()
    {
        $this->beConstructedWith('Anthony', 'Veraa2');
        $this->shouldThrow(InvalidAuthorException::class)->duringInstantiation();
    }

    function it_returns_full_name_of_author()
    {
        $this->beConstructedWith('Grzegorz R.R.', 'Brzęczyszcząkiewić');
        $this->fullName()->shouldReturn('Grzegorz R.R. Brzęczyszcząkiewić');
    }

    function it_it_comparable(Author $sameAuthor, Author $otherAuthor)
    {
        $this->beConstructedWith('Joanne Kathleen', 'Rowling');

        $sameAuthor->fullName()->willReturn('Joanne Kathleen Rowling');
        $this->equals($sameAuthor)->shouldBe(true);

        $otherAuthor->fullName()->willReturn('George R.R. Martin');
        $this->equals($otherAuthor)->shouldBe(false);
    }
}
