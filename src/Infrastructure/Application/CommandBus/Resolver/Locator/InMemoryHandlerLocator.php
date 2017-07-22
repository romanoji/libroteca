<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Application\CommandBus\Resolver\Locator;

use RJozwiak\Libroteca\Application\CommandHandler;

class InMemoryHandlerLocator implements HandlerLocator
{
    /**
     * @var CommandHandler[]
     */
    private $handlers = [];

    /**
     * InMemoryHandlerLocator constructor.
     * @param CommandHandler[] $handlers
     * @throws \RuntimeException
     */
    public function __construct(array $handlers = [])
    {
        foreach ($handlers as $handler) {
            $this->addHandler($handler);
        }
    }

    /**
     * @param CommandHandler $handler
     * @throws \RuntimeException
     */
    public function addHandler(CommandHandler $handler)
    {
        $this->handlers[get_class($handler)] = $handler;
    }

    /**
     * @param string $handlerName
     * @return CommandHandler
     * @throws \RuntimeException
     */
    public function getHandler(string $handlerName) : CommandHandler
    {
        if (!isset($this->handlers[$handlerName])) {
            throw new \RuntimeException("Handler $handlerName is not defined.");
        }

        return $this->handlers[$handlerName];
    }
}
