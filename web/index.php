<?php

//use RJozwiak\Libroteca\UI\Web\Symfony;
use RJozwiak\Libroteca\UI\Web\Lumen;

require_once __DIR__.'/../vendor/autoload.php';

/* Choose your framework: */

// Symfony //
//$debugMode = true;
//Symfony\Application::run($debugMode);

// Phalcon //
Lumen\Application::run();
