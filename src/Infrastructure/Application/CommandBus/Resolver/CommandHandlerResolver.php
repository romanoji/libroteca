<?php

namespace RJozwiak\Libroteca\Infrastructure\Application\CommandBus\Resolver;

use RJozwiak\Libroteca\Application\Command;
use RJozwiak\Libroteca\Infrastructure\Application\CommandBus\Resolver\Inflector\HandlerInflector;
use RJozwiak\Libroteca\Infrastructure\Application\CommandBus\Resolver\Locator\HandlerLocator;

class CommandHandlerResolver
{
    /** @var HandlerInflector */
    private $inflector;

    /** @var HandlerLocator */
    private $locator;

    /**
     * CommandHandlerResolver constructor.
     * @param HandlerInflector $inflector
     * @param HandlerLocator $locator
     */
    public function __construct(HandlerInflector $inflector, HandlerLocator $locator)
    {
        $this->inflector = $inflector;
        $this->locator = $locator;
    }

    /**
     * @param Command $command
     * @return object
     */
    public function resolve(Command $command)
    {
        $handlerName = $this->inflector->inflect($command);
        $handler = $this->locator->getHandler($handlerName);

        return $handler;
    }
}
