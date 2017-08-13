<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\UI\Web\Symfony\Kernel;

use Psr\Log\LoggerInterface;
use RJozwiak\Libroteca\UI\Web\Symfony\Controller\Controller;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;

class ContainerAwareControllerResolver extends ControllerResolver
{
    /** @var Container */
    private $container;

    /**
     * @param LoggerInterface|null $logger
     * @param Container $container
     */
    public function __construct(LoggerInterface $logger = null, Container $container)
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
