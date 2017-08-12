<?php

namespace spec\RJozwiak\Libroteca\Domain\Model\Book;

use PhpSpec\ObjectBehavior;
use RJozwiak\Libroteca\Domain\Model\AggregateRoot;
use RJozwiak\Libroteca\Domain\Model\Book\Author;
use RJozwiak\Libroteca\Domain\Model\Book\Book;
use RJozwiak\Libroteca\Domain\Model\Book\BookID;
use RJozwiak\Libroteca\Domain\Model\Book\ISBN\ISBN;
use RJozwiak\Libroteca\Domain\Model\Book\ISBN\ISBN13;
use RJozwiak\Libroteca\Domain\Model\Book\ISBN\NullISBN;
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

    function it_is_aggregate_root()
    {
        $this->shouldBeAnInstanceOf(AggregateRoot::class);
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

    function it_throws_exception_when_no_authors_provided()
    {
        $this->beConstructedWith(
            new BookID(1),
            new ISBN13('9781568650548'),
            [],
            new Title("The Mote in God's Eye")
        );

        $this
            ->shouldThrow(new \InvalidArgumentException('No authors provided.'))
            ->duringInstantiation();
    }

    function it_changes_book_data(
        ISBN $isbn,
        Author $author,
        Title $title
    ) {
        $authors = [$author];

        $this->setData($isbn, $authors, $title);

        $this->isbn()->shouldReturn($isbn);
        $this->authors()->shouldReturn($authors);
        $this->title()->shouldReturn($title);
    }

    function it_removes_isbn()
    {
        $this->removeISBN();

        $this->isbn()->shouldReturnAnInstanceOf(NullISBN::class);
    }
}
