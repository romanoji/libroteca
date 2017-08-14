<?php

namespace spec\RJozwiak\Libroteca\Infrastructure\Application\CommandBus\Simple\Resolver\Locator;

use PhpSpec\ObjectBehavior;
use RJozwiak\Libroteca\Application\CommandHandler;
use RJozwiak\Libroteca\Infrastructure\Application\CommandBus\Simple\Resolver\Locator\InMemoryHandlerLocator;

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

    function it_registers_handler_to_collection()
    {
        $this->registerHandler(new SomeOtherHandler());
    }

    function it_returns_handler_from_collection()
    {
        $someHandler = new SomeHandler();
        $someOtherHandler = new SomeOtherHandler();

        $this->beConstructedWith([$someHandler]);
        $this->registerHandler($someOtherHandler);

        $this->getHandler(SomeHandler::class)->shouldReturn($someHandler);
        $this->getHandler(SomeOtherHandler::class)->shouldReturn($someOtherHandler);
    }

    function it_throws_exception_when_trying_to_get_non_existing_handler()
    {
        $nonExistingHandler = 'SomeNonExistingHandler';

        $this
            ->shouldThrow(new \RuntimeException("Handler $nonExistingHandler is not registered."))
            ->during('getHandler', [$nonExistingHandler]);
    }
}

class SomeHandler implements CommandHandler
{
}

class SomeOtherHandler implements CommandHandler
{
}
