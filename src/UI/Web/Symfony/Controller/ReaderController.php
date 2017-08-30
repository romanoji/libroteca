<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\UI\Web\Symfony\Controller;

use Ramsey\Uuid\Uuid;
use RJozwiak\Libroteca\Application\Command\RegisterReader;
use RJozwiak\Libroteca\Application\Query\ReaderQueryService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/readers", name="readers")
 */
class ReaderController extends ApiController
{
    /**
     * @Route(methods={"GET"})
     */
    public function indexAction()
    {
        return $this->wrapRequest(function () {
            return $this->successResponse(
                $this->readers()->getAll()
            );
        });
    }

    /**
     * @Route("/{id}", methods={"GET"})
     */
    public function getAction(string $id)
    {
        return $this->wrapRequest(function () use ($id) {
            return $this->successResponse(
                $this->readers()->getOne(Uuid::fromString($id)->toString())
            );
        });
    }

    /**
     * @Route(methods={"POST"})
     */
    public function createAction()
    {
        return $this->wrapRequest(function () {
            $uuid = Uuid::uuid4();

            $this->handle(
                new RegisterReader(
                    $uuid->toString(),
                    $this->requestParam('name'),
                    $this->requestParam('surname'),
                    $this->requestParam('email'),
                    $this->requestParam('phone')
                )
            );

            return $this->successResponse(['id' => $uuid], Response::HTTP_CREATED);
        });
    }

    /**
     * @return ReaderQueryService
     */
    private function readers(): ReaderQueryService
    {
        return $this->get('doctrine_reader_query_service');
    }
}
