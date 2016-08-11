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
        $this->beConstructedWith('Joanne Kathleen Rowling');
        $this->shouldHaveType(Author::class);
    }

    function it_throws_exception_on_empty_name()
    {
        $this->beConstructedWith('');
        $this->shouldThrow(InvalidAuthorException::class)->duringInstantiation();
    }

    function it_throws_exception_when_name_is_too_long()
    {
        $this->beConstructedWith(
            'Peaches Honeyblossom Michelle Charlotte Angel Vanessa '.
            'Wolfeschlegelsteinhausenbergerdorffwelchevoralternwaren'
        );
        $this->shouldThrow(InvalidAuthorException::class)->duringInstantiation();
    }

    function it_throws_exception_when_name_has_invalid_format()
    {
        $this->beConstructedWith('Anthon7 Veraas');
        $this->shouldThrow(InvalidAuthorException::class)->duringInstantiation();
    }

    function it_returns_full_name_of_author()
    {
        $this->beConstructedWith('Grzegorz R.R. Brzęczyszcząkiewić');
        $this->name()->shouldReturn('Grzegorz R.R. Brzęczyszcząkiewić');
    }

    function it_it_comparable(Author $sameAuthor, Author $otherAuthor)
    {
        $this->beConstructedWith('Joanne Kathleen Rowling');

        $sameAuthor->name()->willReturn('Joanne Kathleen Rowling');
        $this->equals($sameAuthor)->shouldBe(true);

        $otherAuthor->name()->willReturn('George R.R. Martin');
        $this->equals($otherAuthor)->shouldBe(false);
    }
}
