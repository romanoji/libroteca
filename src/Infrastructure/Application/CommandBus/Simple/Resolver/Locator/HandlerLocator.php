<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Application\CommandBus\Simple\Resolver\Locator;

use RJozwiak\Libroteca\Application\CommandHandler;

interface HandlerLocator
{
    /**
     * @param string $handlerName
     * @return CommandHandler
     */
    public function getHandler(string $handlerName) : CommandHandler;
}
