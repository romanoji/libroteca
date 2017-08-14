<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\UI\Web\Symfony\Controller;

use Ramsey\Uuid\Uuid;
use RJozwiak\Libroteca\Application\Command\ImportBooks;
use RJozwiak\Libroteca\Application\Command\RegisterBook;
use RJozwiak\Libroteca\Application\Command\UpdateBook;
use RJozwiak\Libroteca\Application\Query\BookQueryService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/books", name="books")
 */
class BookController extends ApiController
{
    /**
     * @Route(methods={"GET"})
     */
    public function indexAction()
    {
        return $this->wrapRequest(function () {
            return $this->successResponse(
                $this->books()->getAll()
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
                $this->books()->getOne(Uuid::fromString($id)->toString())
            );
        });
    }

    /**
     * @Route(
     *     methods={"POST"},
     *     condition="request.headers.get('Content-Type') matches '/^multipart\\/form-data/'"
     * )
     */
    public function importAction()
    {
        return $this->wrapRequest(function () {
            $importFile = $this->request()->files->get('import_file');

            $this->handle(new ImportBooks($importFile));

            return $this->successResponse(null, Response::HTTP_NO_CONTENT);
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
                new RegisterBook(
                    $uuid->toString(),
                    $this->requestParam('isbn', false),
                    $this->requestArrayParam('authors'),
                    $this->requestParam('title')
                )
            );

            return $this->successResponse(['id' => $uuid], Response::HTTP_CREATED);
        });
    }

    /**
     * @Route("/{id}", methods={"PUT"})
     */
    public function updateAction(string $id)
    {
        return $this->wrapRequest(function () use ($id) {
            $this->handle(
                new UpdateBook(
                    Uuid::fromString($id)->toString(),
                    $this->requestParam('isbn', false),
                    $this->requestArrayParam('authors'),
                    $this->requestParam('title')
                )
            );

            return $this->successResponse();
        });
    }

    /**
     * @return BookQueryService
     */
    private function books() : BookQueryService
    {
        return $this->get('doctrine_book_query_service');
    }
}
