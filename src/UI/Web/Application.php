<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\UI\Web;

abstract class Application
{
    /** @var array */
    protected $options;

    /**
     * @param array $options
     */
    protected function __construct(array $options = [])
    {
        $this->options = $options;
    }

    public static function run(array $options = []) : void
    {
        (new static($options))->bootstrap();
    }

    abstract protected function bootstrap(): void;

    /**
     * @return string
     */
    public static function rootDir() : string
    {
        return __DIR__.'/../../..';
    }

    /**
     * @return string
     */
    public static function cacheDir() : string
    {
        return self::rootDir().'/var/cache';
    }

    /**
     * @return string
     */
    public static function logsDir() : string
    {
        return self::rootDir().'/var/logs';
    }

}
