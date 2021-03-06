<?php

namespace spec\RJozwiak\Libroteca\Domain\Model\Book\ISBN;

use RJozwiak\Libroteca\Domain\Model\Book\ISBN\ISBN10;
use RJozwiak\Libroteca\Domain\Model\Book\ISBN\ISBN13;
use RJozwiak\Libroteca\Domain\Model\Book\ISBN\ISBNFactory;
use PhpSpec\ObjectBehavior;
use RJozwiak\Libroteca\Domain\Model\Book\ISBN\NullISBN;

class ISBNFactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ISBNFactory::class);
    }

    function it_creates_nullisbn_on_default()
    {
        $this->create()->shouldBeLike(new NullISBN());
    }

    function it_creates_nullisbn_from_empty_string()
    {
        $this->create('')->shouldBeLike(new NullISBN());
    }

    function it_creates_isbn10_from_valid_isbn_string()
    {
        $this->create('0-7475-4624-X')->shouldBeLike(new ISBN10('074754624X'));
    }

    function it_creates_isbn13_from_valid_isbn_string()
    {
        $this->create('978-0553801477')->shouldBeLike(new ISBN13('9780553801477'));
    }

    function it_throw_exception_on_invalid_isbn_length()
    {
        $this->shouldThrow(new \InvalidArgumentException('Invalid isbn length.'))->during('create', ['1234']);
    }
}
