<?php

namespace RJozwiak\Libroteca\Application;

interface CommandBus
{
    /**
     * @param Command $command
     * @return void
     */
    public function handle(Command $command);
}
