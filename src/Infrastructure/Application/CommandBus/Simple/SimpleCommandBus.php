<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Application\CommandBus\Simple;

use RJozwiak\Libroteca\Application\Command;
use RJozwiak\Libroteca\Application\CommandBus;
use RJozwiak\Libroteca\Application\CommandHandler;
use RJozwiak\Libroteca\Infrastructure\Application\CommandBus\Simple\Resolver\CommandHandlerResolver;

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
     */
    public function handle(Command $command) : void
    {
        // TODO: typed Handler vs. MethodNotFoundException

        $this->handlerFor($command)->execute($command);
    }

    /**
     * @param Command $command
     * @return CommandHandler
     */
    private function handlerFor(Command $command) : CommandHandler
    {
        return $this->resolver->resolve($command);
    }
}
