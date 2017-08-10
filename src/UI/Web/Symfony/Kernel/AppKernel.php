<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\UI\Web\Symfony\Kernel;

use Symfony\Component\HttpKernel\HttpKernel;

class AppKernel extends HttpKernel
{
    /**
     * @return string
     */
    public static function appRootDir() : string
    {
        return __DIR__.'/../../../../..';
    }

    /**
     * @return string
     */
    public static function appCacheDir() : string
    {
        return self::appRootDir().'/var/cache';
    }

    /**
     * @return string
     */
    public static function appLogsDir() : string
    {
        return self::appRootDir().'/var/logs';
    }
}
