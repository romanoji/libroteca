<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Type;
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
        'text[]' => 'MartinGeorgiev\Doctrine\DBAL\Types\TextArray'
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

        return EntityManager::create(
            $connectionConfig,
            self::configuration()
        );
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
            Type::addType($name, $class);
        }

        self::$dbalTypesRegistered = true;
    }

    /**
     * @return \Doctrine\ORM\Configuration
     */
    private static function configuration()
    {
        $config = Setup::createYAMLMetadataConfiguration(
            [self::MAPPINGS_PATH, self::EMBEDDABLE_PATH], true
        );

        $config->setAutoGenerateProxyClasses(false);

        return $config;
    }
}
