<?php

use RJozwiak\Libroteca\UI\Web\Symfony;
use RJozwiak\Libroteca\UI\Web\Lumen;

require_once __DIR__.'/../vendor/autoload.php';

switch (getenv('FRAMEWORK')) {
    case 'symfony':
        Symfony\Application::run(['debug' => true]);
        break;
    case 'lumen':
        Lumen\Application::run();
        break;
    default:
        throw new RuntimeException('No framework specified.');
}
