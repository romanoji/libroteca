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

    function it_is_comparable(Author $sameAuthor, Author $otherAuthor)
    {
        $sameAuthor->name()->willReturn('George R.R. Martin');
        $otherAuthor->name()->willReturn('J.K. Rowling');

        $this->equals($sameAuthor)->shouldBe(true);
        $this->equals($otherAuthor)->shouldBe(false);
    }
}
