<?php

namespace spec\RJozwiak\Libroteca\Domain\Model\Book;

use RJozwiak\Libroteca\Domain\Model\Book\Title;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TitleSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('A Dance with Dragons');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Title::class);
    }

    function it_returns_title()
    {
        $this->title()->shouldBe('A Dance with Dragons');
    }

    function it_is_comparable(Title $sameTitle, Title $otherTitle)
    {
        $sameTitle->title()->willReturn('A Dance with Dragons');
        $otherTitle->title()->willReturn('Harry Potter');

        $this->equals($sameTitle)->shouldBe(true);
        $this->equals($otherTitle)->shouldBe(false);
    }
}
