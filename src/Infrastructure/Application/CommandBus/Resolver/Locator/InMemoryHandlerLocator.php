<?php

namespace RJozwiak\Libroteca\Infrastructure\Application\CommandBus\Resolver\Locator;

class InMemoryHandlerLocator implements HandlerLocator
{
    /**
     * @var object[]
     */
    private $handlers = [];

    /**
     * InMemoryHandlerLocator constructor.
     * @param object[] $handlers
     */
    public function __construct(array $handlers = [])
    {
        foreach ($handlers as $handler) {
            $this->addHandler($handler);
        }
    }

    /**
     * @param object $handler
     * @throws \RuntimeException
     */
    public function addHandler($handler)
    {
        if (!is_object($handler)) {
            throw new \RuntimeException('Passed argument is not an object.');
        }

        $this->handlers[get_class($handler)] = $handler;
    }

    /**
     * @param string $handlerName
     * @return object
     * @throws \RuntimeException
     */
    public function getHandler($handlerName)
    {
        if (!isset($this->handlers[$handlerName])) {
            throw new \RuntimeException("Handler $handlerName is not defined.");
        }

        return $this->handlers[$handlerName];
    }
}
