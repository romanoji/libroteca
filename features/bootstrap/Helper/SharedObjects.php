<?php
declare(strict_types=1);

namespace Helper;

use Helper\SharedObjects\NoSuchObjectForKeyException;
use Helper\SharedObjects\ObjectsRegistry;

class SharedObjects
{
    /** @var ObjectsRegistry */
    private static $objects;

    /**
     * @param string $class
     * @param array $params
     * @return object
     */
    public static function loadOrCreate(string $class, array $params = [])
    {
        self::initObjectsRegistry();

        $key = self::generateHash($class, $params);
        try {
            $object = self::$objects->load($key);
        } catch (NoSuchObjectForKeyException $e) {
            $object = new $class(...$params);
            self::$objects->save($key, $object);
        }

        return $object;
    }

    private static function initObjectsRegistry()
    {
        if (self::$objects === null) {
            self::$objects = new ObjectsRegistry();
        }
    }

    /**
     * @param string $class
     * @param array $params
     * @return string
     */
    private static function generateHash(string $class, array $params): string
    {
        return sha1($class . ':' . serialize($params));
    }

    public static function clearSharedObjects(): void
    {
        self::$objects = new ObjectsRegistry();
    }
}
