<?php

namespace spec\RJozwiak\Libroteca\Infrastructure\Application\CommandBus\Simple\Resolver;

use PhpSpec\ObjectBehavior;
use RJozwiak\Libroteca\Application\Command;
use RJozwiak\Libroteca\Application\CommandHandler;
use RJozwiak\Libroteca\Infrastructure\Application\CommandBus\Simple\Resolver\CommandHandlerResolver;
use RJozwiak\Libroteca\Infrastructure\Application\CommandBus\Simple\Resolver\Inflector\HandlerInflector;
use RJozwiak\Libroteca\Infrastructure\Application\CommandBus\Simple\Resolver\Locator\HandlerLocator;

class CommandHandlerResolverSpec extends ObjectBehavior
{
    function let(HandlerInflector $inflector, HandlerLocator $locator)
    {
        $this->beConstructedWith($inflector, $locator);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CommandHandlerResolver::class);
    }

    function it_resolves_command_and_returns_handler(
        SomeAction $command,
        SomeActionHandler $handler,
        HandlerInflector $inflector,
        HandlerLocator $locator
    ) {
        $inflector->inflect($command)
            ->willReturn(SomeActionHandler::class)
            ->shouldBeCalled();
        $locator->getHandler(SomeActionHandler::class)
            ->willReturn($handler)
            ->shouldBeCalled();

        $this->resolve($command)->shouldReturn($handler);
    }
}

class SomeAction implements Command
{
}

class SomeActionHandler implements CommandHandler
{
}
