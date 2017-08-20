<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\UI\Web\Lumen;

use RJozwiak\Libroteca\UI\Web\Application as BaseApplication;

class Application extends BaseApplication
{
    protected function bootstrap(): void
    {
        $app = require_once __DIR__.'/Application/bootstrap/app.php';
        $app->run();
    }
}
