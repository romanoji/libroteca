<?php

namespace spec\RJozwiak\Libroteca\Domain\Model\Book;

use RJozwiak\Libroteca\Domain\Model\Book\Author;
use PhpSpec\ObjectBehavior;

class AuthorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith('George R.R. Martin');
        $this->shouldHaveType(Author::class);
    }

    function it_returns_name()
    {
        $this->beConstructedWith('Grzegorz R.R. Brzęczyszcząkiewić');
        $this->name()->shouldBe('Grzegorz R.R. Brzęczyszcząkiewić');
    }

    function it_throws_exception_on_empty_name()
    {
        $this->beConstructedWith('');
        $this->shouldThrow(new \InvalidArgumentException('Empty name.'))->duringInstantiation();
    }

    function it_throws_exception_on_invalid_name_format()
    {
        $this->beConstructedWith('Ge0rg3.123');
        $this->shouldThrow(new \InvalidArgumentException('Invalid name format.'))->duringInstantiation();
    }

    function it_is_comparable(Author $sameAuthor, Author $otherAuthor)
    {
        $this->beConstructedWith('George R.R. Martin');

        $sameAuthor->name()->willReturn('George R.R. Martin');
        $this->equals($sameAuthor)->shouldBe(true);

        $otherAuthor->name()->willReturn('J.K. Rowling');
        $this->equals($otherAuthor)->shouldBe(false);
    }
}
