<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\UI\Web\Symfony\Routing;

use Symfony\Component\Routing\Loader\AnnotationClassLoader;
use Symfony\Component\Routing\Route;

class AnnotationRouteControllerLoader extends AnnotationClassLoader
{
    /**
     * @param Route $route
     * @param \ReflectionClass $class
     * @param \ReflectionMethod $method
     * @param mixed $annot Annotation class instance
     */
    protected function configureRoute(
        Route $route,
        \ReflectionClass $class,
        \ReflectionMethod $method,
        $annot
    ) {
        $route->setDefault('_controller', $class->getName().'::'.$method->getName());
    }
}
