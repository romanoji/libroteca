<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\UI\Web\Symfony\Controller;

use Ramsey\Uuid\Uuid;
use RJozwiak\Libroteca\Application\Command\RegisterBookCopy;
use RJozwiak\Libroteca\Application\Command\UpdateBookCopyRemarks;
use RJozwiak\Libroteca\Application\Query\BookCopyQueryService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/books/{bookID}/copies")
 */
class BookCopyController extends ApiController
{
    /**
     * @Route(methods={"GET"})
     */
    public function getByBookAction(string $bookID)
    {
        return $this->wrapRequest(function () use ($bookID) {
            $embedOngoingLoans = $this->requestParam('embed', false) == 'ongoing_loans';

            return $this->successResponse(
                $this->booksCopies()->getAllByBook(
                    Uuid::fromString($bookID)->toString(),
                    $embedOngoingLoans
                )
            );
        });
    }

    /**
     * @Route(methods={"POST"})
     */
    public function createAction(string $bookID)
    {
        return $this->wrapRequest(function () use ($bookID) {
            $uuid = Uuid::uuid4();

            $this->handle(
                new RegisterBookCopy(
                    $uuid->toString(),
                    Uuid::fromString($bookID)->toString()
                )
            );

            return $this->successResponse(['id' => $uuid], Response::HTTP_CREATED);
        });
    }

    /**
     * @Route("/{id}", methods={"PUT"})
     */
    public function updateAction(string $bookID, string $id)
    {
        return $this->wrapRequest(function () use ($id) {
            $this->handle(
                new UpdateBookCopyRemarks(
                    Uuid::fromString($id)->toString(),
                    $this->requestParam('remarks')
                )
            );

            return $this->successResponse();
        });
    }

    /**
     * @return BookCopyQueryService
     */
    private function booksCopies() : BookCopyQueryService
    {
        return $this->get('doctrine_book_copy_query_service');
    }
}
