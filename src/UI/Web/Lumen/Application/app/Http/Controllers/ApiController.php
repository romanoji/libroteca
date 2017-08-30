<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Lumen\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Laravel\Lumen\Routing\Controller;
use League\Tactician\CommandBus;
use RJozwiak\Libroteca\Application\Command;

class ApiController extends Controller
{
    /** @var CommandBus */
    protected $commandBus;

    /**
     * @param CommandBus $commandBus
     */
    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @param Command $command
     */
    protected function handle(Command $command)
    {
        $this->commandBus->handle($command);
    }

    // DUPLICATED ON PURPOSE (RJozwiak\Libroteca\UI\Web\Symfony\Controller\ApiController)
    /**
     * @param null|array $data
     * @param int $statusCode
     * @return JsonResponse
     */
    protected function successResponse(
        array $data = null,
        int $statusCode = Response::HTTP_OK
    ): JsonResponse {
        $responseData = ['success' => true];

        if ($data !== null) {
            $responseData['data'] = $data;
        }

        return new JsonResponse($responseData, $statusCode);
    }

    /**
     * @param string|null $message
     * @param array|null $data
     * @param int $statusCode
     * @return JsonResponse
     */
    protected function clientErrorResponse(
        string $message = null,
        array $data = null,
        int $statusCode = Response::HTTP_UNPROCESSABLE_ENTITY
    ): JsonResponse {
        $responseData = ['success' => false];

        $error = [];
        if ($message) {
            $error['message'] = $message;
        }
        if ($data) {
            $error['data'] = $data;
        }

        if ($error) {
            $responseData['error'] = $error;
        }

        return new JsonResponse($responseData, $statusCode);
    }

    /**
     * @param string|array|null $message
     * @param int $statusCode
     * @return JsonResponse
     */
    protected function errorResponse(
        $message = null,
        int $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR
    ): JsonResponse {
        return new JsonResponse($message, $statusCode);
    }
}
