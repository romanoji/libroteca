<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Application\CommandBus\Simple\Resolver\Inflector;

use RJozwiak\Libroteca\Application\Command;

interface HandlerInflector
{
    /**
     * Returns Handler class name for given Command
     * @param Command $command
     * @return string
     */
    public function inflect(Command $command) : string;
}
