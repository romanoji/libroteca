<?php

namespace spec\RJozwiak\Libroteca\Domain\Model;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use RJozwiak\Libroteca\Domain\Model\ID;

class IDSpec extends ObjectBehavior
{
    function let()
    {
        $this->beAnInstanceOf(FakeID::class);
        $this->beConstructedWith(1337);
    }

    function it_returns_identifier()
    {
        $this->id()->shouldReturn(1337);
    }

    function it_is_comparable(FakeID $sameID, FakeID $otherID, OtherFakeID $otherTypeSameID)
    {
        $sameID->id()->willReturn(1337);
        $sameID->getClass()->willReturn('FakeID');

        $otherID->id()->willReturn(7331);
        $otherID->getClass()->willReturn('FakeID');

        $otherTypeSameID->id()->willReturn(1337);
        $otherTypeSameID->getClass()->willReturn('OtherFakeID');

        $this->equals($sameID)->shouldBe(true);
        $this->equals($otherID)->shouldBe(false);
        $this->equals($otherTypeSameID)->shouldBe(false);
    }
}

class FakeID extends ID
{
    /**
     * @param ID $id
     * @return bool
     */
    public function equals(ID $id)
    {
        return
            $this->getClass() === $id->getClass() &&
            $this->id() === $id->id();
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return 'FakeID';
    }
}

class OtherFakeID extends FakeID
{
    /**
     * @return string
     */
    public function getClass()
    {
        return 'OtherFakeID';
    }
}
