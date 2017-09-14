<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\UI\Web\Symfony\Controller;

use Ramsey\Uuid\Uuid;
use RJozwiak\Libroteca\Application\Command\RegisterReader;
use RJozwiak\Libroteca\Application\Command\SendNotificationToReader;
use RJozwiak\Libroteca\Application\Query\ReaderQueryService;
use RJozwiak\Libroteca\Application\Query\Specification\OrSpecification;
use RJozwiak\Libroteca\Domain\Model\Reader\Notification\NotificationType;
use RJozwiak\Libroteca\Infrastructure\Application\Query\Doctrine\Specification\DBAL\Reader\{
    EmailEqualsSpecification, NameLikeSpecification, PhoneEqualsSpecification, SurnameLikeSpecification
};
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/readers", name="readers")
 */
class ReaderController extends ApiController
{
    /**
     * @Route(methods={"GET"})
     */
    public function indexAction()
    {
        return $this->wrapRequest(function () {
            $filtersParam = $this->requestArrayParam('filters', false);

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

                $readers = $this->readers()->getAllByCriteria($specification);
            } else {
                $readers = $this->readers()->getAll();
            }

            return $this->successResponse($readers);
        });
    }

    /**
     * @Route("/{id}", methods={"GET"})
     */
    public function getAction(string $id)
    {
        return $this->wrapRequest(function () use ($id) {
            return $this->successResponse(
                $this->readers()->getOne(Uuid::fromString($id)->toString())
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
                new RegisterReader(
                    $uuid->toString(),
                    $this->requestParam('name'),
                    $this->requestParam('surname'),
                    $this->requestParam('email'),
                    $this->requestParam('phone')
                )
            );

            return $this->successResponse(['id' => $uuid], null, Response::HTTP_CREATED);
        });
    }

    /**
     * @return ReaderQueryService
     */
    private function readers(): ReaderQueryService
    {
        return $this->get('doctrine_reader_query_service');
    }
}
