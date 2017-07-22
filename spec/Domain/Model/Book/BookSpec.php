<?php

namespace spec\RJozwiak\Libroteca\Domain\Model\Book;

use PhpSpec\ObjectBehavior;
use RJozwiak\Libroteca\Domain\Model\Book\Author;
use RJozwiak\Libroteca\Domain\Model\Book\Book;
use RJozwiak\Libroteca\Domain\Model\Book\BookID;
use RJozwiak\Libroteca\Domain\Model\Book\ISBN\ISBN13;
use RJozwiak\Libroteca\Domain\Model\Book\Title;

class BookSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(
            new BookID(1),
            new ISBN13('9781568650548'),
            [new Author('Larry Niven'), new Author('Jerry Pournelle')],
            new Title("The Mote in God's Eye")
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Book::class);
    }

    function it_returns_identifier()
    {
        $this->id()->id()->shouldReturn(1);
    }

    function it_returns_isbn()
    {
        $this->isbn()->isbn()->shouldReturn('9781568650548');
    }

    function it_returns_title()
    {
        $this->title()->title()->shouldReturn("The Mote in God's Eye");
    }

    function it_returns_authors()
    {
        $this->authors()->shouldBeLike([new Author('Larry Niven'), new Author('Jerry Pournelle')]);
    }
}
