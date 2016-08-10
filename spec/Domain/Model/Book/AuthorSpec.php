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
        $this->shouldThrow(InvalidAuthorException::class)->during('__construct', ['', 'Rowling']);
    }

    function it_throws_exception_on_name_length_beyond_range()
    {
        $this
            ->shouldThrow(InvalidAuthorException::class)
            ->during('__construct', ['Peaches Honeyblossom Michelle Charlotte Angel Vanessa', 'Geldof']);
    }

    function it_throws_exception_when_name_has_invalid_format()
    {
        $this
            ->shouldThrow(InvalidAuthorException::class)
            ->during('__construct', ['Anthon1', 'Veraas']);
    }

    function it_throws_exception_on_empty_surname()
    {
        $this->shouldThrow(InvalidAuthorException::class)->during('__construct', ['George R.R.', '']);
    }

    function it_throws_exception_on_surname_length_beyond_range()
    {
        $this
            ->shouldThrow(InvalidAuthorException::class)
            ->during('__construct', ['Hubert Blaine', 'Wolfeschlegelsteinhausenbergerdorffwelchevoralternwaren']);
    }

    function it_throws_exception_when_surname_has_invalid_format()
    {
        $this
            ->shouldThrow(InvalidAuthorException::class)
            ->during('__construct', ['Anthony', 'Veraa2']);
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
