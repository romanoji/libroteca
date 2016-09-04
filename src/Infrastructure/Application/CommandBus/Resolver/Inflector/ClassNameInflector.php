<?php

namespace RJozwiak\Libroteca\Infrastructure\Application\CommandBus\Resolver\Inflector;

use RJozwiak\Libroteca\Application\Command;

class ClassNameInflector implements HandlerInflector
{
    /**
     * Handler class name for Command
     * @param Command $command
     * @return string
     */
    public function inflect(Command $command)
    {
        return get_class($command).'Handler';
    }
}
