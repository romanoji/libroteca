<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Application\CommandBus\Simple\Resolver;

use RJozwiak\Libroteca\Application\Command;
use RJozwiak\Libroteca\Application\CommandHandler;
use RJozwiak\Libroteca\Infrastructure\Application\CommandBus\Simple\Resolver\Inflector\HandlerInflector;
use RJozwiak\Libroteca\Infrastructure\Application\CommandBus\Simple\Resolver\Locator\HandlerLocator;

class CommandHandlerResolver
{
    /** @var HandlerInflector */
    private $inflector;

    /** @var HandlerLocator */
    private $locator;

    /**
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
     * @return CommandHandler
     */
    public function resolve(Command $command): CommandHandler
    {
        $handlerName = $this->inflector->inflect($command);
        $handler = $this->locator->getHandler($handlerName);

        return $handler;
    }
}
