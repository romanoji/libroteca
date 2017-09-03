<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\UI\Web\Symfony\Controller;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Exception\InvalidParameterException;

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
     * @throws ServiceCircularReferenceException
     * @throws ServiceNotFoundException
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

    /**
     * It returns value for requested parameter by $name.
     *
     * When parameter isn't found, it checks if it's $required:
     * - if it's $required it throws an UnprocessableEntityHttpException.
     * - if it's not $required, then it returns $default value.
     *
     * There's no need to specify $default value, when parameter is $required.
     * When it's both $required and have specified $default value
     * it will still return an exception, when requested parameter doesn't exist.
     *
     * @param string $name
     * @param bool $required
     * @param null|mixed $default
     * @return mixed
     * @throws UnprocessableEntityHttpException
     */
    protected function requestParam(
        string $name,
        $required = true,
        $default = null
    ) {
        $value = $this->request()->get($name);

        if ($value === null) {
            if ($required) {
                throw new UnprocessableEntityHttpException(
                    sprintf('Parameter `%s` is required.', $name)
                );
            } else {
                return $default;
            }
        }

        return $value;
    }

    // TODO: validate request params via Symfony Validator
    /**
     * @param string $name
     * @param bool $required
     * @param array $default
     * @return mixed
     * @throws InvalidParameterException
     * @throws UnprocessableEntityHttpException
     */
    protected function requestArrayParam(
        string $name,
        $required = true,
        $default = []
    ) {
        return $this->assertArrayParam($name, $this->requestParam($name, $required, $default));
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return mixed
     * @throws InvalidParameterException
     */
    private function assertArrayParam(string $name, $value)
    {
        if ($value && !is_array($value)) {
            throw new InvalidParameterException("`{$name}` parameter must be an array.");
        }

        return $value;
    }

    /**
     * @param string $name
     * @param string $format
     * @param bool $required
     * @param \DateTimeImmutable|null $default
     * @return \DateTimeImmutable
     * @throws InvalidParameterException
     * @throws UnprocessableEntityHttpException
     */
    protected function requestDateTimeParam(
        string $name,
        string $format = 'Y-m-d H:i:s',
        $required = true,
        \DateTimeImmutable $default = null
    ) {
        $paramValue = $this->requestParam($name, $required);

        if ($paramValue === null && !$required) {
            return $default;
        }

        $value = \DateTimeImmutable::createFromFormat($format, $paramValue);

        if (!$value) {
            throw new InvalidParameterException("`{$name}` has invalid date/time format. Requested format {$format}.");
        }

        return $value;
    }

    /**
     * @return Request
     */
    protected function request(): Request
    {
        return $this->get('request');
    }
}
