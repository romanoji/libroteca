<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\UI\Web\Symfony\Controller;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * (Almost) clean copy of Symfony\Bundle\FrameworkBundle\Controller\Controller.
 * This one lacks ControllerTrait.
 *
 * @package RJozwiak\Libroteca\UI\Web\Symfony\Controller
 */
abstract class Controller implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * Returns true if the service id is defined.
     *
     * @param string $id The service id
     *
     * @return bool true if the service id is defined, false otherwise
     */
    protected function has($id)
    {
        return $this->container->has($id);
    }

    /**
     * Gets a container service by its id.
     *
     * @param string $id The service id
     * @return object The service
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     */
    protected function get($id)
    {
        return $this->container->get($id);
    }

    /**
     * Gets a container configuration parameter by its name.
     *
     * @param string $name The parameter name
     * @return mixed
     * @throws \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     */
    protected function getParameter($name)
    {
        return $this->container->getParameter($name);
    }

}
