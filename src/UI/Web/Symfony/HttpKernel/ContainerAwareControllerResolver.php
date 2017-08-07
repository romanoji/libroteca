<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\UI\Web\Symfony\HttpKernel;

use Psr\Log\LoggerInterface;
use RJozwiak\Libroteca\UI\Web\Symfony\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;

class ContainerAwareControllerResolver extends ControllerResolver
{
    /** @var ContainerBuilder */
    private $container;

    public function __construct(LoggerInterface $logger = null, ContainerBuilder $container)
    {
        parent::__construct($logger);

        $this->container = $container;
    }

    /**
     * Returns an instantiated container aware controller.
     *
     * @param string $class A class name
     *
     * @return object
     */
    protected function instantiateController($class)
    {
        $controller = new $class();

        if ($controller instanceof Controller) {
            $controller->setContainer($this->container);
        }

        return $controller;
    }
}
