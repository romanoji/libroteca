<?php

use RJozwiak\Libroteca\UI\Web\Symfony;
use RJozwiak\Libroteca\UI\Web\Lumen;

require_once __DIR__.'/../vendor/autoload.php';

switch (getenv('FRAMEWORK')) {
    case 'symfony':
        $debugMode = true;
        Symfony\Application::run($debugMode);
        break;
    case 'laravel':
        Lumen\Application::run();
        break;
    default:
        throw new RuntimeException('No framework specified.');
}
