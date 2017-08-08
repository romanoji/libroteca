<?php

namespace spec\RJozwiak\Libroteca\Infrastructure\Application\CommandBus\Simple;

use PhpSpec\ObjectBehavior;
use RJozwiak\Libroteca\Application\Command;
use RJozwiak\Libroteca\Application\CommandHandler;
use RJozwiak\Libroteca\Infrastructure\Application\CommandBus\Simple\Resolver\CommandHandlerResolver;
use RJozwiak\Libroteca\Infrastructure\Application\CommandBus\Simple\SimpleCommandBus;

class SimpleCommandBusSpec extends ObjectBehavior
{
    function let(CommandHandlerResolver $resolver)
    {
        $this->beConstructedWith($resolver);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(SimpleCommandBus::class);
    }

    function it_handles_provided_command(
        CommandHandlerResolver $resolver,
        SomeAction $command,
        SomeActionHandler $handler
    ) {
        $resolver->resolve($command)
            ->shouldBeCalled()
            ->willReturn($handler);

        $this->handle($command);
    }
}

class SomeAction implements Command
{
}

class SomeActionHandler implements CommandHandler
{
    public function execute()
    {
    }
}
