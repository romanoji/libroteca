<?php

namespace spec\RJozwiak\Libroteca\Domain\Model\Reader;

use RJozwiak\Libroteca\Domain\Model\Reader\Email;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EmailSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('john.kowalsky@mail.com');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Email::class);
    }

    function it_throws_exception_on_invalid_email_format()
    {
        $this->beConstructedWith('john.@mail');
        $this->shouldThrow(new \InvalidArgumentException('Invalid email format.'))->duringInstantiation();
    }

    function it_is_comparable(Email $email)
    {
        $email->email()->willReturn('john.kowalsky@mail.com');

        $this->equals($email)->shouldBe(true);
    }
}