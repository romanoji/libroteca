<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\UI\Web\Symfony\Controller;

use RJozwiak\Libroteca\Application\Command;
use RJozwiak\Libroteca\Application\CommandBus;
use RJozwiak\Libroteca\Domain\Model\AggregateNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

abstract class ApiController extends Controller
{
    private const RESOURCE_NOT_FOUND_MSG = 'Resource not found.';

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

    /**
     * @param callable $responseFn
     * @return JsonResponse
     */
    protected function wrapRequest(callable $responseFn) : JsonResponse
    {
        try {
            return $this->successResponse($responseFn());
        } catch (\InvalidArgumentException | \DomainException | UnprocessableEntityHttpException $e) {
            return $this->clientErrorResponse($e->getMessage());
        } catch (ResourceNotFoundException | AggregateNotFoundException $e) {
            return $this->clientErrorResponse(
                self::RESOURCE_NOT_FOUND_MSG,
                null,
                Response::HTTP_NOT_FOUND
            );
        } catch (HttpException $e) {
            return $this->errorResponse(
                $e->getMessage(),
                $e->getStatusCode()
            );
        } catch (\Exception $e) {
            // TODO: log these errors
            if ($this->container->getParameter('kernel.debug')) {
                return $this->errorResponse($e->getMessage());
            }

            return $this->errorResponse();
        }
    }

    // TODO: create ResponseBuilder
    /**
     * @param array $data
     * @return JsonResponse
     */
    protected function successResponse(array $data) : JsonResponse
    {
        $responseData = [
            'success' => true,
            'data' => $data
        ];

        return new JsonResponse($responseData, Response::HTTP_OK);
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
    ) : JsonResponse {
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
     * @param string|null $message
     * @param int $statusCode
     * @return JsonResponse
     */
    protected function errorResponse(
        string $message = null,
        int $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR
    ) : JsonResponse {
        return new JsonResponse($message, $statusCode);
    }
}
