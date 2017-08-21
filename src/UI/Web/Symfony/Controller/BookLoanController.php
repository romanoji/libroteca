<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\UI\Web\Symfony\Controller;

use Ramsey\Uuid\Uuid;
use RJozwiak\Libroteca\Application\Command\EndBookLoan;
use RJozwiak\Libroteca\Application\Command\LendBookCopy;
use RJozwiak\Libroteca\Application\Command\ProlongBookLoan;
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
    }

    /**
     * @Route(methods={"GET"})
     */
    public function indexAction()
    {
        return $this->wrapRequest(function () {
            return $this->successResponse(
                $this->bookLoans()->getAll(
                    $this->requestArrayParam('filters', false)
                )
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

    /**
     * @Route("/{id}", methods={"PUT"})
     */
    public function updateAction(string $id)
    {
        return $this->wrapRequest(function () use ($id) {
            $parameter = 'action';
            $action = $this->requestParam($parameter);

            switch ($action) {
                case 'prolong':
                    $this->handle(
                        new ProlongBookLoan(
                            Uuid::fromString($id)->toString(),
                            $this->requestDateTimeParam('prolong_to', 'Y-m-d'),
                            new \DateTimeImmutable()
                        )
                    );
                    break;
                case 'end':
                    $this->handle(
                        new EndBookLoan(
                            Uuid::fromString($id)->toString(),
                            new \DateTimeImmutable(),
                            $this->requestParam('remarks', false)
                        )
                    );
                    break;
                default:
                    throw new \InvalidArgumentException(
                        sprintf(
                            "Allowed values for `%s` parameter are: [%s].",
                            $parameter,
                            implode(', ', ['prolong', 'end'])
                        )
                    );
            }

            return $this->successResponse();
        });
    }

    /**
     * @return BookLoanQueryService
     */
    private function bookLoans() : BookLoanQueryService
    {
        return $this->get('doctrine_book_loan_query_service');
    }
}
