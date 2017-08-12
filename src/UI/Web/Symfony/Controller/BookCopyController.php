<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\UI\Web\Symfony\Controller;

use Ramsey\Uuid\Uuid;
use RJozwiak\Libroteca\Application\Query\BookCopyQueryService;
use Symfony\Component\Routing\Annotation\Route;

class BookCopyController extends ApiController
{
    /**
     * @Route("/books/{bookID}/copies")
     */
    public function getByBookAction(string $bookID)
    {
        return $this->wrapRequest(function () use ($bookID) {
            return $this->successResponse(
                $this->booksCopies()->getAllByBook(Uuid::fromString($bookID)->toString())
            );
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
