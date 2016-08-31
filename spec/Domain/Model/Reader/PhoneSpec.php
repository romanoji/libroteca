<?php

namespace spec\RJozwiak\Libroteca\Domain\Model\Reader;

use RJozwiak\Libroteca\Domain\Model\Reader\Phone;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PhoneSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('+48 123456789');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Phone::class);
    }

    function it_throws_exception_on_invalid_email_format()
    {
        $this->beConstructedWith('+48 I23A567B9');
        $this->shouldThrow(new \InvalidArgumentException('Invalid phone format.'))->duringInstantiation();
    }

    function it_returns_phone()
    {
        $this->phone()->shouldReturn('+48 123456789');
    }

    function it_is_comparable(Phone $samePhone, Phone $otherPhone)
    {
        $samePhone->phone()->willReturn('+48 123456789');
        $this->equals($samePhone)->shouldBe(true);

        $otherPhone->phone()->willReturn('+48 987654321');
        $this->equals($otherPhone)->shouldBe(false);
    }
}
