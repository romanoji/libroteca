<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Application;

interface CommandBus
{
    /**
     * @param Command $command
     */
    public function handle(Command $command): void;
}
