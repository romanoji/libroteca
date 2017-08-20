<?php

namespace RJozwiak\Libroteca\Lumen\Exceptions;

use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Ramsey\Uuid\Exception\InvalidUuidStringException;
use RJozwiak\Libroteca\Domain\Model\AggregateNotFoundException;
use RJozwiak\Libroteca\Domain\Model\DomainLogicException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    private const RESOURCE_NOT_FOUND_MSG = 'Resource not found.';

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
        DomainLogicException::class,
        InvalidUuidStringException::class
    ];

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $e
     * @return JsonResponse
     */
    public function render($request, \Exception $e)
    {
        $statusCode = JsonResponse::HTTP_INTERNAL_SERVER_ERROR;
        $message = $e->getMessage();

        switch (true) {
            case $e instanceof AggregateNotFoundException:
            case $e instanceof NotFoundHttpException:
                $statusCode = JsonResponse::HTTP_NOT_FOUND;
                $message = self::RESOURCE_NOT_FOUND_MSG;
                break;
            case $e instanceof DomainLogicException:
            case $e instanceof InvalidUuidStringException:
                $statusCode = JsonResponse::HTTP_UNPROCESSABLE_ENTITY;
                break;
            case $e instanceof ValidationException:
                $statusCode = JsonResponse::HTTP_UNPROCESSABLE_ENTITY;
                $data = $e->validator->getMessageBag()->toArray();
                break;
        }

        $response = ['success' => false];

        $error = [];
        if (!empty($message)) {
            $error['message'] = $message;
        }
        if (!empty($data)) {
            $error['data'] = $data;
        }

        if ($error) {
            $response['error'] = $error;
        }

        return new JsonResponse($response, $statusCode);
    }
}
