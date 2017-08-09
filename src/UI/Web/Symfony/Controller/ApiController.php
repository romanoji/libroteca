<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\UI\Web\Symfony\Controller;

use RJozwiak\Libroteca\Application\Command;
use RJozwiak\Libroteca\Application\CommandBus;
use Symfony\Component\HttpFoundation\Request;

abstract class ApiController extends Controller
{
    /**
     * @return Request
     */
    protected function request()
    {
        return $this->get('request');
    }

    /**
     * @param Command $command
     */
    protected function handle(Command $command)
    {
        $this->commandBus()->handle($command);
    }

    /**
     * @return CommandBus
     */
    private function commandBus()
    {
        return $this->get('command_bus');
    }

    // TODO: create ResponseBuilder
    /**
     * @param array $data
     * @return array
     */
    protected function successResponse(array $data) : array
    {
        return [
            'success' => true,
            'data' => $data
        ];
    }

    /**
     * @param string|null $message
     * @param array|null $data
     * @return array
     */
    protected function errorResponse(string $message = null, array $data = null) : array
    {
        $response = ['success' => false];

        $error = [];
        if ($message) {
            $error['message'] = $message;
        }
        if ($data) {
            $error['data'] = $data;
        }

        if ($error) {
            $response['error'] = $error;
        }

        return $response;
    }
}
