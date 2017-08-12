<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\UI\Web\Symfony\Controller;

use Ramsey\Uuid\Uuid;
use RJozwiak\Libroteca\Application\Command\LendBookCopy;
use RJozwiak\Libroteca\Application\Command\RegisterBookCopy;
use RJozwiak\Libroteca\Application\Command\UpdateBookCopyRemarks;
use RJozwiak\Libroteca\Application\Query\BookCopyQueryService;
use RJozwiak\Libroteca\Application\Query\BookLoanQueryService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/book_loans")
 */
class BookLoanController extends ApiController
{
    /**
     * @Route("/{id}", methods={"GET"})
     */
    public function getAction(string $id)
    {
        return $this->wrapRequest(function () use ($id) {
            return $this->successResponse(
                $this->bookLoans()->getOne(Uuid::fromString($id)->toString())
            );
        });
        // TODO: add info about loans?
    }

    /**
     * @Route(methods={"POST"})
     */
    public function createAction()
    {
        return $this->wrapRequest(function () {
            $uuid = Uuid::uuid4();

            $this->handle(
                new LendBookCopy(
                    $uuid->toString(),
                    $this->requestParam('reader_id'),
                    $this->requestParam('book_copy_id'),
                    $this->requestDateTimeParam('due_date', 'Y-m-d'),
                    new \DateTimeImmutable()
                )
            );

            return $this->successResponse(['id' => $uuid], Response::HTTP_CREATED);
        });
    }

    public function prolongAction()
    {

    }

    /**
     * @Route("/{id}", methods={"DELETE"})
     */
    public function deleteAction()
    {

    }

    /**
     * @return BookLoanQueryService
     */
    private function bookLoans() : BookLoanQueryService
    {
        return $this->get('doctrine_book_loan_query_service');
    }
}
