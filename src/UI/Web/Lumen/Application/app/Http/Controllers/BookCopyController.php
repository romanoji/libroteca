<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Lumen\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use League\Tactician\CommandBus;
use Ramsey\Uuid\Uuid;
use RJozwiak\Libroteca\Application\Command\RegisterBookCopy;
use RJozwiak\Libroteca\Application\Command\UpdateBookCopyRemarks;
use RJozwiak\Libroteca\Application\Query\BookCopyQueryService;
use RJozwiak\Libroteca\Infrastructure\Persistence\Lumen;

class BookCopyController extends ApiController
{
    /** @var BookCopyQueryService */
    private $booksCopies;

    /**
     * @param CommandBus $commandBus
     * @param BookCopyQueryService $booksCopies
     */
    public function __construct(
        CommandBus $commandBus,
        BookCopyQueryService $booksCopies
    ) {
        parent::__construct($commandBus);
        $this->booksCopies = $booksCopies;
    }

    public function index(Request $request, string $bookID)
    {
        $embedOngoingLoans = $request->input('embed', false) == 'ongoing_loans';

        return $this->successResponse(
            $this->booksCopies->getAllByBook($bookID, $embedOngoingLoans)
        );
    }

    public function create(string $bookID)
    {
        $uuid = Uuid::uuid4();

        $this->handle(
            new RegisterBookCopy(
                $uuid->toString(),
                Uuid::fromString($bookID)->toString()
            )
        );

        return $this->successResponse(['id' => $uuid], Response::HTTP_CREATED);
    }

    public function update(Request $request, string $bookID, string $id)
    {
        $this->validate($request, ['remarks' => 'required']);

        $this->handle(
            new UpdateBookCopyRemarks(
                Uuid::fromString($id)->toString(),
                $request->input('remarks')
            )
        );

        return $this->successResponse();
    }
}
