<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Application\CommandBus\Resolver\Inflector;

use RJozwiak\Libroteca\Application\Command;

class ClassNameInflector implements HandlerInflector
{
    /**
     * Returns Handler class name for given Command
     * @param Command $command
     * @return string
     */
    public function inflect(Command $command) : string
    {
        return get_class($command).'Handler';
    }
}
