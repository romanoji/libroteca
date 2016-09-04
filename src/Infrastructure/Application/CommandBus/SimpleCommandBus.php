<?php

namespace RJozwiak\Libroteca\Infrastructure\Application\CommandBus;

use RJozwiak\Libroteca\Application\Command;
use RJozwiak\Libroteca\Application\CommandBus;
use RJozwiak\Libroteca\Infrastructure\Application\CommandBus\Resolver\CommandHandlerResolver;

class SimpleCommandBus implements CommandBus
{
    /** @var CommandHandlerResolver  */
    private $resolver;

    /**
     * SimpleCommandBus constructor.
     * @param CommandHandlerResolver $resolver
     */
    public function __construct(CommandHandlerResolver $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * @param Command $command
     * @return void
     */
    public function handle(Command $command)
    {
        // TODO: typed Handler vs. MethodNotFoundException

        $this->handler($command)->execute($command);
    }

    /**
     * @param Command $command
     * @return object
     */
    private function handler(Command $command)
    {
        return $this->resolver->resolve($command);
    }
}
