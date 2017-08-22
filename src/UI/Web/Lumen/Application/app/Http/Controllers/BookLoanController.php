<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Lumen\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use League\Tactician\CommandBus;
use Ramsey\Uuid\Uuid;
use RJozwiak\Libroteca\Application\Command\EndBookLoan;
use RJozwiak\Libroteca\Application\Command\LendBookCopy;
use RJozwiak\Libroteca\Application\Command\ProlongBookLoan;
use RJozwiak\Libroteca\Application\Query\BookLoanQueryService;
use RJozwiak\Libroteca\Infrastructure\Persistence\Lumen;

class BookLoanController extends ApiController
{
    private const DATE_FORMAT = 'Y-m-d';

    /** @var BookLoanQueryService */
    private $bookLoans;

    /**
     * @param CommandBus $commandBus
     * @param BookLoanQueryService $bookLoans
     */
    public function __construct(
        CommandBus $commandBus,
        BookLoanQueryService $bookLoans
    ) {
        parent::__construct($commandBus);
        $this->bookLoans = $bookLoans;
    }

    public function index(Request $request)
    {
        $this->validate($request, [
            'filters.ended' => 'boolean',
            'filters.prolonged' => 'boolean'
        ]);

        return $this->successResponse(
            $this->bookLoans->getAll(
                $request->input('filters', [])
            )
        );
    }

    public function get(string $id)
    {
        return $this->successResponse(
            $this->bookLoans->getOne(Uuid::fromString($id)->toString())
        );
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'reader_id' => 'required',
            'book_copy_id' => 'required',
            'due_date' => sprintf('required|date_format:%s', self::DATE_FORMAT)
        ]);

        $uuid = Uuid::uuid4();

        $this->handle(
            new LendBookCopy(
                $uuid->toString(),
                $request->input('reader_id'),
                $request->input('book_copy_id'),
                \DateTimeImmutable::createFromFormat(
                    self::DATE_FORMAT,
                    $request->input('due_date')
                ),
                new \DateTimeImmutable()
            )
        );

        return $this->successResponse(['id' => $uuid], Response::HTTP_CREATED);
    }

    public function update(Request $request, string $id)
    {
        $parameter = 'action';
        $allowedActions = ['prolong', 'end'];

        $this->validate(
            $request,
            ['action' => 'required'],
            ['action.required' => sprintf(
                "Allowed values for `%s` parameter are: [%s].",
                $parameter,
                implode(', ', $allowedActions)
            )]
        );

        switch ($request->input($parameter)) {
            case 'prolong':
                return $this->prolongBookLoan($request, $id);
            case 'end':
                return $this->endBookLoan($request, $id);
        }

        return $this->successResponse();
    }

    private function prolongBookLoan(Request $request, string $id)
    {
        $this->validate($request, [
            'prolong_to' => sprintf('required|date_format:%s', self::DATE_FORMAT)
        ]);

        $this->handle(
            new ProlongBookLoan(
                Uuid::fromString($id)->toString(),
                \DateTimeImmutable::createFromFormat(
                    self::DATE_FORMAT,
                    $request->input('prolong_to')
                ),
                new \DateTimeImmutable()
            )
        );

        return $this->successResponse();
    }

    private function endBookLoan(Request $request, string $id)
    {
        $this->handle(
            new EndBookLoan(
                Uuid::fromString($id)->toString(),
                new \DateTimeImmutable(),
                $request->input('remarks')
            )
        );

        return $this->successResponse();
    }
}
