<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Application\CommandBus\Tactician\MethodNameInflector;

use League\Tactician\Handler\MethodNameInflector\MethodNameInflector;

class ExecuteInflector implements MethodNameInflector
{
    /**
     * {@inheritdoc}
     */
    public function inflect($command, $commandHandler)
    {
        return 'execute';
    }
}
