<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Lumen\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use League\Tactician\CommandBus;
use Ramsey\Uuid\Uuid;
use RJozwiak\Libroteca\Application\Command\ImportBooks;
use RJozwiak\Libroteca\Application\Command\RegisterBook;
use RJozwiak\Libroteca\Application\Command\UpdateBook;
use RJozwiak\Libroteca\Application\Query\BookQueryService;
use RJozwiak\Libroteca\Infrastructure\Persistence\Lumen;

class BookController extends ApiController
{
    /** @var BookQueryService */
    private $books;

    /**
     * @param CommandBus $commandBus
     * @param BookQueryService $books
     */
    public function __construct(
        CommandBus $commandBus,
        BookQueryService $books
    ) {
        parent::__construct($commandBus);
        $this->books = $books;
    }

    public function index()
    {
        return $this->successResponse(
            $this->books->getAll()
        );
    }

    public function get(string $id)
    {
        return $this->successResponse(
            $this->books->getOne(Uuid::fromString($id)->toString())
        );
    }

    public function create(Request $request)
    {
        if (strpos($request->header('Content-Type'), 'multipart/form-data') === 0) {
            return $this->importBooks($request);
        } else {
            return $this->createBook($request);
        }
    }

    private function createBook(Request $request)
    {
        $this->validateBookParams($request);

        $uuid = Uuid::uuid4();

        $this->handle(
            new RegisterBook(
                $uuid->toString(),
                $request->input('isbn', null),
                $request->input('authors'),
                $request->input('title')
            )
        );

        return $this->successResponse(['id' => $uuid], Response::HTTP_CREATED);

    }

    private function importBooks(Request $request)
    {
        $importFile = $request->file('import_file');

        if ($importFile === null) {
            return $this->clientErrorResponse('Import file is required.');
        }

        $this->handle(new ImportBooks($importFile));

        return $this->successResponse(null, Response::HTTP_NO_CONTENT);

    }

    public function update(Request $request, string $id)
    {
        $this->validateBookParams($request);

        $this->handle(
            new UpdateBook(
                Uuid::fromString($id)->toString(),
                $request->input('isbn', null),
                $request->input('authors'),
                $request->input('title')
            )
        );

        return $this->successResponse();
    }

    private function validateBookParams(Request $request)
    {
        $this->validate($request, [
            'authors' => 'required',
            'title' => 'required'
        ]);
    }
}
