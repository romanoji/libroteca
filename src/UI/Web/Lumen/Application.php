<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\UI\Web\Lumen;

class Application
{
    public static function run()
    {
        (new self())->bootstrap();
    }

    private function bootstrap(): void
    {
        require_once __DIR__.'/Application/public/index.php';
    }
}
