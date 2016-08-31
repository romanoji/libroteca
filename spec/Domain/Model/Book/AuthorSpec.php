<?php

namespace spec\RJozwiak\Libroteca\Domain\Model\Book;

use RJozwiak\Libroteca\Domain\Model\Book\Author;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AuthorSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('George R.R. Martin');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Author::class);
    }

    function it_returns_name()
    {
        $this->name()->shouldBe('George R.R. Martin');
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
        $sameAuthor->name()->willReturn('George R.R. Martin');
        $this->equals($sameAuthor)->shouldBe(true);

        $otherAuthor->name()->willReturn('J.K. Rowling');
        $this->equals($otherAuthor)->shouldBe(false);
    }
}
