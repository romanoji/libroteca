<?php

use RJozwiak\Libroteca\UI\Web\Symfony;
//use RJozwiak\Libroteca\UI\Web\Phalcon;

require_once __DIR__.'/../vendor/autoload.php';

/* Choose your framework: */

// Symfony //
$debugMode = true;
Symfony\Application::run($debugMode);

// Phalcon //
//Phalcon\Application::run();
