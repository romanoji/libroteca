<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Application\CommandBus\Tactician\Locator;

use League\Tactician\Exception\MissingHandlerException;
use League\Tactician\Handler\Locator\HandlerLocator;
use RJozwiak\Libroteca\Application\CommandHandler;

class InMemoryHandlerLocator implements HandlerLocator
{
    /**
     * @var CommandHandler[]
     */
    protected $handlers = [];

    /**
     * @param CommandHandler[] $handlers
     */
    public function __construct(array $handlers = [])
    {
        $this->addHandlers($handlers);
    }

    /**
     * @param CommandHandler $handler
     */
    public function addHandler(CommandHandler $handler)
    {
        $this->handlers[get_class($handler)] = $handler;
    }

    /**
     * @param CommandHandler[] $handlers
     */
    public function addHandlers(array $handlers)
    {
        foreach ($handlers as $handler) {
            $this->addHandler($handler);
        }
    }

    /**
     * @param string $commandName
     * @return CommandHandler
     * @throws MissingHandlerException
     */
    public function getHandlerForCommand($commandName): CommandHandler
    {
        $handlerName = $this->commandToHandler($commandName);
        if (!isset($this->handlers[$handlerName])) {
            throw MissingHandlerException::forCommand($commandName);
        }

        return $this->handlers[$handlerName];
    }

    /**
     * @param string $commandName
     * @return string
     */
    private function commandToHandler($commandName)
    {
        return $commandName.'Handler';
    }
}
