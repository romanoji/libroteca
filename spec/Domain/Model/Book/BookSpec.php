<?php

namespace spec\RJozwiak\Libroteca\Domain\Model\Book;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use RJozwiak\Libroteca\Domain\Model\Book\Author;
use RJozwiak\Libroteca\Domain\Model\Book\Book;
use RJozwiak\Libroteca\Domain\Model\Book\BookID;
use RJozwiak\Libroteca\Domain\Model\Book\ISBN\ISBN13;
use RJozwiak\Libroteca\Domain\Model\Book\ISBN\ISBNFactory;
use RJozwiak\Libroteca\Domain\Model\Book\Title;

class BookSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith(
            new BookID(1),
            new ISBN13('9781568650548'),
            [new Author('Larry Niven'), new Author('Jerry Pournelle')],
            new Title("The Mote in God's Eye")
        );

        $this->shouldHaveType(Book::class);
    }
}
