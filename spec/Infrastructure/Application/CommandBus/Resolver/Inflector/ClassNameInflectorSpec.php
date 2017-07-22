<?php

namespace spec\RJozwiak\Libroteca\Infrastructure\Application\CommandBus\Resolver\Inflector;

use RJozwiak\Libroteca\Application\Command;
use RJozwiak\Libroteca\Infrastructure\Application\CommandBus\Resolver\Inflector\ClassNameInflector;
use PhpSpec\ObjectBehavior;

class ClassNameInflectorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ClassNameInflector::class);
    }

    function it_inflects_command_to_handler()
    {
        $this->inflect(new SomeAction())->shouldReturn(SomeActionHandler::class);
    }
}

class SomeAction implements Command
{
}

class SomeActionHandler
{
}
