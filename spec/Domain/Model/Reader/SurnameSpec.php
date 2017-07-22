<?php

namespace spec\RJozwiak\Libroteca\Domain\Model\Reader;

use RJozwiak\Libroteca\Domain\Model\Reader\Surname;
use PhpSpec\ObjectBehavior;

class SurnameSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('Kowalsky');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Surname::class);
    }

    function it_throws_exception_on_empty()
    {
        $this->beConstructedWith('');

        $this->shouldThrow(new \InvalidArgumentException('Empty surname.'))->duringInstantiation();
    }

    function it_throws_exception_when_it_is_too_long()
    {
        $this->beConstructedWith(
            'Wolfeschlegelsteinhausenbergerdorffwelchevoralternwaren'.
            'gewissenhaftschaferswessenschafewarenwohlgepﬂegeundsorgfaltig'
        );

        $this->shouldThrow(new \InvalidArgumentException('Too long surname.'))->duringInstantiation();
    }

    function it_throws_exception_on_invalid_format()
    {
        $this->beConstructedWith('K0wa1sky-');

        $this->shouldThrow(new \InvalidArgumentException('Invalid surname format.'))->duringInstantiation();
    }

    function it_is_comparable()
    {
        $sameSurname = new Surname('Kowalsky');
        $this->equals($sameSurname)->shouldBe(true);

        $otherSurname = new Surname('Novak');
        $this->equals($otherSurname)->shouldBe(false);
    }
}
