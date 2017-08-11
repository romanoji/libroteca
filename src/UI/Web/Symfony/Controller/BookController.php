<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\UI\Web\Symfony\Controller;

use Ramsey\Uuid\Uuid;
use RJozwiak\Libroteca\Application\Command\RegisterBook;
use RJozwiak\Libroteca\Application\Query\BookQueryService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Exception\InvalidParameterException;

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
            return $this->books()->getAll();
        });
    }

    /**
     * @Route("/{id}", methods={"GET"})
     */
    public function getAction(string $id)
    {
        return $this->wrapRequest(function () use ($id) {
            return $this->books()->getOne(Uuid::fromString($id)->toString());
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

            return ['id' => $uuid];
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
