<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Setup;

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

    private static $customStringFunctions = [
        'ALL_OF' => 'MartinGeorgiev\Doctrine\ORM\Query\AST\Functions\All',
        'ANY_OF' => 'MartinGeorgiev\Doctrine\ORM\Query\AST\Functions\Any',
        'IN_ARRAY' => 'MartinGeorgiev\Doctrine\ORM\Query\AST\Functions\InArray',
        'ARRAY' => 'MartinGeorgiev\Doctrine\ORM\Query\AST\Functions\Arr',
        'CONTAINS' => 'MartinGeorgiev\Doctrine\ORM\Query\AST\Functions\Contains',
        'OVERLAPS' => 'MartinGeorgiev\Doctrine\ORM\Query\AST\Functions\Overlaps',
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
    public static function create($connectionConfig) : EntityManagerInterface
    {
        self::registerDBALTypes();

        $em = EntityManager::create(
            $connectionConfig,
            self::configuration()
        );

        self::registerDoctrineTypeMappings($em->getConnection());

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
     * @param Connection $connection
     * @throws \Doctrine\DBAL\DBALException
     */
    private static function registerDoctrineTypeMappings(Connection $connection)
    {
        $connection->getDatabasePlatform()->registerDoctrineTypeMapping('text[]', 'text[]');
        $connection->getDatabasePlatform()->registerDoctrineTypeMapping('_text', 'text[]');
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

        return $config;
    }

    /**
     * @param Configuration $config
     */
    private static function registerCustomFunctions(Configuration $config)
    {
        $config->setCustomStringFunctions(self::$customStringFunctions);
    }
}
