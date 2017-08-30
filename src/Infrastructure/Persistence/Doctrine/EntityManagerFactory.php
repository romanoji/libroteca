<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Persistence\Doctrine;

use Doctrine\Common\Cache\ArrayCache;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Setup;
use RJozwiak\Libroteca\Domain\Model\Book\Book;
use RJozwiak\Libroteca\Domain\Model\BookCopy\BookCopy;
use RJozwiak\Libroteca\Domain\Model\BookLoan\BookLoan;
use RJozwiak\Libroteca\Domain\Model\Reader\Reader;

class EntityManagerFactory
{
    private const MAPPINGS_PATH = __DIR__.'/Mapping';
    private const EMBEDDABLE_PATH = self::MAPPINGS_PATH.'/Embeddable';

    /** @var array */
    private static $dbalTypes = [
        'BookID' => 'RJozwiak\Libroteca\Infrastructure\Domain\Model\Book\DoctrineBookID',
        'BookCopyID' => 'RJozwiak\Libroteca\Infrastructure\Domain\Model\BookCopy\DoctrineBookCopyID',
        'BookLoanID' => 'RJozwiak\Libroteca\Infrastructure\Domain\Model\BookLoan\DoctrineBookLoanID',
        'ReaderID' => 'RJozwiak\Libroteca\Infrastructure\Domain\Model\Reader\DoctrineReaderID',
        'ISBN' => 'RJozwiak\Libroteca\Infrastructure\Domain\Model\Book\ISBN\DoctrineISBN',
        'Authors' => 'RJozwiak\Libroteca\Infrastructure\Persistence\Doctrine\Type\AuthorsType',
        'text[]' => 'MartinGeorgiev\Doctrine\DBAL\Types\TextArray',

        // TODO: to drop after bumping doctrine to 2.6 along with dependency
        'date_immutable' => 'VasekPurchart\Doctrine\Type\DateTimeImmutable\DateImmutableType',
        'datetime_immutable' => 'VasekPurchart\Doctrine\Type\DateTimeImmutable\DateTimeImmutableType',
        'datetimetz_immutable' => 'VasekPurchart\Doctrine\Type\DateTimeImmutable\DateTimeTzImmutableType',
        'time_immutable' => 'VasekPurchart\Doctrine\Type\DateTimeImmutable\TimeImmutableType',
    ];

    /** @var array */
    private static $customStringFunctions = [
        'ALL_OF' => 'MartinGeorgiev\Doctrine\ORM\Query\AST\Functions\All',
        'ANY_OF' => 'MartinGeorgiev\Doctrine\ORM\Query\AST\Functions\Any',
        'IN_ARRAY' => 'MartinGeorgiev\Doctrine\ORM\Query\AST\Functions\InArray',
        'ARRAY' => 'MartinGeorgiev\Doctrine\ORM\Query\AST\Functions\Arr',
        'CONTAINS' => 'MartinGeorgiev\Doctrine\ORM\Query\AST\Functions\Contains',
        'OVERLAPS' => 'MartinGeorgiev\Doctrine\ORM\Query\AST\Functions\Overlaps',
    ];

    /** @var array */
    private static $classesDependencies = [
        Book::class => [BookCopy::class],
        BookCopy::class => [BookLoan::class],
        Reader::class => [BookLoan::class]
    ];

    /** @var bool */
    private static $dbalTypesRegistered = false;

    /**
     * @param array|Connection $connectionConfig
     * @return EntityManagerInterface
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\ORM\ORMException
     * @throws \InvalidArgumentException
     */
    public static function create($connectionConfig): EntityManagerInterface
    {
        self::registerDBALTypes();

        $em = EntityManager::create(
            $connectionConfig,
            self::configuration()
        );

        self::registerDoctrineTypeMappings($em->getConnection());
        self::defineDependencies($em);

        return $em;
    }

    /**
     * @throws \Doctrine\DBAL\DBALException
     */
    private static function registerDBALTypes()
    {
        if (self::$dbalTypesRegistered) {
            return;
        }

        foreach (self::$dbalTypes as $name => $class) {
            if (!Type::hasType($name)) {
                Type::addType($name, $class);
            }
        }

        self::$dbalTypesRegistered = true;
    }

    /**
     * @return Configuration
     */
    private static function configuration()
    {
        $config = Setup::createYAMLMetadataConfiguration(
            [self::MAPPINGS_PATH, self::EMBEDDABLE_PATH], true
        );

        self::registerCustomFunctions($config);

        $config->setAutoGenerateProxyClasses(false);
        $config->setMetadataCacheImpl(new ArrayCache());
        $config->setQueryCacheImpl(new ArrayCache());

        return $config;
    }

    /**
     * @param Configuration $config
     */
    private static function registerCustomFunctions(Configuration $config)
    {
        $config->setCustomStringFunctions(self::$customStringFunctions);
    }

    /**
     * @param Connection $connection
     * @throws \Doctrine\DBAL\DBALException
     */
    private static function registerDoctrineTypeMappings(Connection $connection)
    {
        $connection->getDatabasePlatform()->registerDoctrineTypeMapping('text[]', 'text[]');
        $connection->getDatabasePlatform()->registerDoctrineTypeMapping('_text', 'text[]');
    }

    /**
     * It's a hack for EntityManager#flush that cannot resolve dependencies without defined associations.
     * WARNING! It works for only one flush call.
     * TODO: find better way to define dependencies
     *
     * @param EntityManagerInterface $entityManager
     */
    private static function defineDependencies(EntityManagerInterface $entityManager)
    {
        $unitOfWork = $entityManager->getUnitOfWork();
        $commitOrderCalculator = $unitOfWork->getCommitOrderCalculator();

        $classesMetadata = [];

        foreach (self::$classesDependencies as $from => $toDependencies) {
            if (!isset($classesMetadata[$from])) {
                $classesMetadata[$from] = $entityManager->getClassMetadata($from);
            }

            foreach ($toDependencies as $to) {
                if (!isset($classesMetadata[$to])) {
                    $classesMetadata[$to] = $entityManager->getClassMetadata($to);
                }

                $commitOrderCalculator->addDependency($classesMetadata[$from], $classesMetadata[$to]);
            }
        }
    }
}
