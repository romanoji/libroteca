<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\UI\Web\Symfony\Controller;

use RJozwiak\Libroteca\Application\Command;
use RJozwiak\Libroteca\Application\CommandBus;
use RJozwiak\Libroteca\Domain\Model\AggregateNotFoundException;
use RJozwiak\Libroteca\Domain\Model\DomainLogicException;
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

    // TODO: wrap *Action methods dynamically with this
    /**
     * @param callable $responseFn
     * @return JsonResponse
     */
    protected function wrapRequest(callable $responseFn) : JsonResponse
    {
        try {
            return $responseFn();
        } catch (\InvalidArgumentException | DomainLogicException | UnprocessableEntityHttpException $e) {
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
                return $this->errorResponse([
                    'exception' => get_class($e),
                    'message' => $e->getMessage(),
                    'trace' => $e->getTrace()
                ]);
            }

            return $this->errorResponse();
        }
    }

    // TODO: create ResponseBuilder
    /**
     * @param null|array $data
     * @param int $statusCode
     * @return JsonResponse
     */
    protected function successResponse(
        array $data = null,
        int $statusCode = Response::HTTP_OK
    ) : JsonResponse {
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
     * @param string|array|null $message
     * @param int $statusCode
     * @return JsonResponse
     */
    protected function errorResponse(
        $message = null,
        int $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR
    ) : JsonResponse {
        return new JsonResponse($message, $statusCode);
    }
}
