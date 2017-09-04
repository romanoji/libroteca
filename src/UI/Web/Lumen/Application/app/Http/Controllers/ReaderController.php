<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Lumen\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use League\Tactician\CommandBus;
use Ramsey\Uuid\Uuid;
use RJozwiak\Libroteca\Application\Command\RegisterReader;
use RJozwiak\Libroteca\Application\Query\ReaderQueryService;
use RJozwiak\Libroteca\Application\Query\Specification\OrSpecification;
use RJozwiak\Libroteca\Infrastructure\Application\Query\Lumen\Specification\Reader\{
    EmailEqualsSpecification, NameLikeSpecification, PhoneEqualsSpecification, SurnameLikeSpecification
};
use RJozwiak\Libroteca\Infrastructure\Persistence\Lumen;

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

    public function index(Request $request)
    {
        $this->validate($request, [
            'filters.name' => 'string',
            'filters.surname' => 'string',
            'filters.email' => 'string',
            'filters.phone' => 'string'
        ]);

        $filtersParam = $request->input('filters', []);

        if ($filtersParam) {
            $filters = array_filter([
                !empty($filtersParam['name']) ? new NameLikeSpecification($filtersParam['name']) : null,
                !empty($filtersParam['surname']) ? new SurnameLikeSpecification($filtersParam['surname']) : null,
                !empty($filtersParam['email']) ? new EmailEqualsSpecification($filtersParam['email']) : null,
                !empty($filtersParam['phone']) ? new PhoneEqualsSpecification($filtersParam['phone']) : null
            ]);

            $specification = null;
            if (!empty($filters)) {
                $specification = new OrSpecification(...$filters);
            }

            $readers = $this->readers->getAllByCriteria($specification);
        } else {
            $readers = $this->readers->getAll();
        }

        return $this->successResponse($readers);
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

        return $this->successResponse(['id' => $uuid], null, Response::HTTP_CREATED);
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
