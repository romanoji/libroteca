<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Lumen\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use League\Tactician\CommandBus;
use Ramsey\Uuid\Uuid;
use RJozwiak\Libroteca\Application\Command\ImportBooks;
use RJozwiak\Libroteca\Application\Command\RegisterBook;
use RJozwiak\Libroteca\Application\Command\RegisterReader;
use RJozwiak\Libroteca\Application\Command\UpdateBook;
use RJozwiak\Libroteca\Application\Query\BookQueryService;
use RJozwiak\Libroteca\Application\Query\ReaderQueryService;
use RJozwiak\Libroteca\Lumen;

class ReaderController extends ApiController
{
    /** @var ReaderQueryService */
    private $readers;

    /**
     * @param CommandBus $commandBus
     * @param ReaderQueryService $readers
     */
    public function __construct(
        CommandBus $commandBus,
        ReaderQueryService $readers
    ) {
        parent::__construct($commandBus);
        $this->readers = $readers;
    }

    public function index()
    {
        return $this->successResponse(
            $this->readers->getAll()
        );
    }

    public function get(string $id)
    {
        return $this->successResponse(
            $this->readers->getOne(Uuid::fromString($id)->toString())
        );
    }

    public function create(Request $request)
    {
        $this->validateReaderParams($request);

        $uuid = Uuid::uuid4();

        $this->handle(
            new RegisterReader(
                $uuid->toString(),
                $request->input('name'),
                $request->input('surname'),
                $request->input('email'),
                $request->input('phone')
            )
        );

        return $this->successResponse(['id' => $uuid], Response::HTTP_CREATED);
    }

    private function validateReaderParams(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'surname' => 'required',
            'email' => 'required',
            'phone' => 'required'
        ]);
    }
}
