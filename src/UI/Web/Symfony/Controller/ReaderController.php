<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\UI\Web\Symfony\Controller;

use Ramsey\Uuid\Uuid;
use RJozwiak\Libroteca\Application\Command\RegisterReader;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/reader", name="reader")
 */
class ReaderController extends ApiController
{
    /**
     * @Route(methods={"GET"})
     */
    public function indexAction()
    {
        return new JsonResponse([]);
    }

    /**
     * @Route("/{id}", methods={"GET"})
     */
    public function getAction(string $id)
    {
        return new JsonResponse([]);
    }

    /**
     * @Route(methods={"POST"})
     */
    public function createAction()
    {
        $uuid = Uuid::uuid4();

        try {
            $this->handle(
                new RegisterReader(
                    $uuid,
                    $this->request()->get('name'),
                    $this->request()->get('surname'),
                    $this->request()->get('email'),
                    $this->request()->get('phone')
                )
            );
        } catch (\InvalidArgumentException | \DomainException $e) {
            return new JsonResponse($this->errorResponse($e->getMessage()));
        }

        return new JsonResponse($this->successResponse(['id' => $uuid]));
    }
}
