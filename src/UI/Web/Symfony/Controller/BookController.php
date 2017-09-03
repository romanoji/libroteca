<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\UI\Web\Symfony\Controller;

use Ramsey\Uuid\Uuid;
use RJozwiak\Libroteca\Application\Command\ImportBooks;
use RJozwiak\Libroteca\Application\Command\RegisterBook;
use RJozwiak\Libroteca\Application\Command\UpdateBook;
use RJozwiak\Libroteca\Application\Query\BookQueryService;
use RJozwiak\Libroteca\Application\Query\Pagination\Pagination;
use RJozwiak\Libroteca\Application\Query\Specification\OrSpecification;
use RJozwiak\Libroteca\Infrastructure\Application\Query\Doctrine\Specification\ORM\Book\{
    ISBNLikeSpecification, TitleLikeSpecification
};
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
            $filtersParam = $this->requestArrayParam('filters', false);
            $page = $this->requestParam('page', false, 1);

            $specification = null;
            if ($filtersParam) {
                $filters = array_filter([
                    !empty($filtersParam['isbn']) ? new ISBNLikeSpecification($filtersParam['isbn']) : null,
                    !empty($filtersParam['title']) ? new TitleLikeSpecification($filtersParam['title']) : null,
                ]);

                if (!empty($filters)) {
                    $specification = new OrSpecification(...$filters);
                }
            }
            $pagination = new Pagination($page);

            $result = $this->books()->getAllByCriteria($specification, $pagination);

            return $this->successResponse($result->data(), $result->metadata()); // TODO: transform $result to response
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
     *     condition="request.headers.get('Content-Type') matches '%^multipart/form-data%'"
     * )
     */
    public function importAction()
    {
        return $this->wrapRequest(function () {
            $importFile = $this->request()->files->get('import_file');

            if ($importFile === null) {
                return $this->clientErrorResponse('Import file is required.');
            }

            $this->handle(new ImportBooks($importFile));

            return $this->successResponse(null, null, Response::HTTP_NO_CONTENT);
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

            return $this->successResponse(['id' => $uuid], null, Response::HTTP_CREATED);
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
    private function books(): BookQueryService
    {
        return $this->get('doctrine_book_query_service');
    }
}
