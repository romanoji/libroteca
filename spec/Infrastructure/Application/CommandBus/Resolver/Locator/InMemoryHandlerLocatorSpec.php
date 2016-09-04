<?php

namespace spec\RJozwiak\Libroteca\Infrastructure\Application\CommandBus\Resolver\Locator;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use RJozwiak\Libroteca\Infrastructure\Application\CommandBus\Resolver\Locator\InMemoryHandlerLocator;

class InMemoryHandlerLocatorSpec extends ObjectBehavior
{
    function let(SomeHandler $handler)
    {
        $this->beConstructedWith([$handler]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(InMemoryHandlerLocator::class);
    }

    function it_add_handler_to_collection()
    {
        $this->addHandler(new SomeOtherHandler());
    }

    function it_returns_handler_from_collection() {
        $someHandler = new SomeHandler();
        $someOtherHandler = new SomeOtherHandler();

        $this->beConstructedWith([$someHandler]);
        $this->addHandler($someOtherHandler);

        $this->getHandler(SomeHandler::class)->shouldReturn($someHandler);
        $this->getHandler(SomeOtherHandler::class)->shouldReturn($someOtherHandler);
    }

    function it_throws_exception_when_trying_to_add_not_an_handler_object()
    {
        $this
            ->shouldThrow(new \RuntimeException('Passed argument is not an object.'))
            ->during('addHandler', [null]);
    }

    function it_throws_exception_when_trying_to_get_non_existing_handler()
    {
        $nonExistingHandler = 'SomeNonExistingHandler';

        $this
            ->shouldThrow(new \RuntimeException("Handler $nonExistingHandler is not defined."))
            ->during('getHandler', [$nonExistingHandler]);
    }
}

class SomeHandler
{
}

class SomeOtherHandler
{
}
