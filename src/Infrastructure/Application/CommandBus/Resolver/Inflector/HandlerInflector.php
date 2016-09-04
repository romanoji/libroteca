<?php

namespace RJozwiak\Libroteca\Infrastructure\Application\CommandBus\Resolver\Inflector;

use RJozwiak\Libroteca\Application\Command;

interface HandlerInflector
{
    /**
     * @param Command $command
     * @return string
     */
    public function inflect(Command $command);
}
