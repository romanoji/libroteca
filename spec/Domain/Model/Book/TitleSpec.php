<?php

namespace spec\RJozwiak\Libroteca\Domain\Model\Book;

use RJozwiak\Libroteca\Domain\Model\Book\InvalidTitleException;
use RJozwiak\Libroteca\Domain\Model\Book\Title;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TitleSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith('The Hunger Games');
        $this->shouldHaveType(Title::class);
    }

    function it_throws_exception_on_empty_title()
    {
        $this->beConstructedWith('');
        $this->shouldThrow(InvalidTitleException::class)->duringInstantiation();
    }

    function it_throws_exception_when_title_length_is_beyond_range()
    {
        $title = "That's the longest book title ever even created, ".
            "because it exceeds maximum length of 100 characters.";

        $this->beConstructedWith($title);
        $this->shouldThrow(InvalidTitleException::class)->duringInstantiation();
    }

    function it_is_comparable(Title $sameTitle, Title $otherTitle)
    {
        $this->beConstructedWith('The Lord of the Rings');

        $sameTitle->title()->willReturn('The Lord of the Rings');
        $this->equals($sameTitle)->shouldBe(true);

        $otherTitle->title()->willReturn('Harry Potter');
        $this->equals($otherTitle)->shouldBe(false);
    }
}
