<?php

namespace spec\RJozwiak\Libroteca\Domain\Model\Reader;

use RJozwiak\Libroteca\Domain\Model\Reader\Name;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class NameSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('John');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Name::class);
    }

    function it_throws_exception_on_empty()
    {
        $this->beConstructedWith('');

        $this->shouldThrow(new \InvalidArgumentException('Empty name.'))->duringInstantiation();
    }

    function it_throws_exception_when_it_is_too_long()
    {
        $this->beConstructedWith(
            'Peaches Honeyblossom Michelle Charlotte Angel Vanessa'
        );

        $this->shouldThrow(new \InvalidArgumentException('Too long name.'))->duringInstantiation();
    }

    function it_throws_exception_on_invalid_format()
    {
        $this->beConstructedWith('J0hn; ');

        $this->shouldThrow(new \InvalidArgumentException('Invalid name format.'))->duringInstantiation();
    }

    function it_is_comparable()
    {
        $sameName = new Name('John');
        $otherName = new Name('Andy');

        $this->equals($sameName)->shouldBe(true);
        $this->equals($otherName)->shouldBe(false);
    }
}
