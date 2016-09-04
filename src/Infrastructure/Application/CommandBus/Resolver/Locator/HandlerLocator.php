<?php

namespace RJozwiak\Libroteca\Infrastructure\Application\CommandBus\Resolver\Locator;

use RJozwiak\Libroteca\Application\Command;

interface HandlerLocator
{
    /**
     * @param string $handlerName
     * @return object
     */
    public function getHandler($handlerName);
}
