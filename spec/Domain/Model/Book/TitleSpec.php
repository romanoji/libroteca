<?php

namespace spec\RJozwiak\Libroteca\Domain\Model\Book;

use RJozwiak\Libroteca\Domain\Model\Book\Title;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TitleSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('The Lord of the Rings');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Title::class);
    }

    function it_throws_exception_on_empty_title()
    {
        $this->beConstructedWith('');
        $this->shouldThrow(new \InvalidArgumentException('Empty title.'))->duringInstantiation();
    }

    function it_returns_title()
    {
        $this->title()->shouldBe('The Lord of the Rings');
    }

    function it_is_comparable(Title $sameTitle, Title $otherTitle)
    {
        $sameTitle->title()->willReturn('The Lord of the Rings');
        $this->equals($sameTitle)->shouldBe(true);

        $otherTitle->title()->willReturn('Harry Potter');
        $this->equals($otherTitle)->shouldBe(false);
    }
}
