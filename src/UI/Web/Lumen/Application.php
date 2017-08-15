<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\UI\Web\Lumen;

class Application
{
    public static function run() : void
    {
        (new self())->bootstrap();
    }

    private function bootstrap(): void
    {
        $app = require_once __DIR__.'/Application/bootstrap/app.php';
        $app->run();
    }
}
