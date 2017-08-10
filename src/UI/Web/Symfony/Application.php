<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\UI\Web\Symfony;

use Doctrine\Common\Annotations\{
    AnnotationReader, AnnotationRegistry
};
use RJozwiak\Libroteca\Infrastructure\DependencyInjection\DependencyInjectionContainerFactory;
use RJozwiak\Libroteca\UI\Web\Symfony\Kernel\AppKernel;
use RJozwiak\Libroteca\UI\Web\Symfony\Kernel\ContainerAwareControllerResolver;
use RJozwiak\Libroteca\UI\Web\Symfony\Routing\AnnotationRouteControllerLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\{
    Controller\ArgumentResolver, EventListener\RouterListener, HttpKernel
};
use Symfony\Component\Routing\{
    Exception\ResourceNotFoundException, Loader\AnnotationDirectoryLoader, Matcher\UrlMatcher, RouteCollection
};

class Application
{
    private const COMPOSER_AUTOLOADER_PATH = __DIR__.'/../../../../vendor/autoload.php';
    private const CONTROLLERS_PATH = __DIR__.'/Controller';

    /** @var ContainerBuilder */
    private $container;

    private function __construct()
    {
        $this->container = DependencyInjectionContainerFactory::create();
        $this->registerAppDirsInContainer();

        $this->registerAnnotations();
    }

    private function registerAppDirsInContainer()
    {
        $this->container->setParameter('kernel.root_dir', realpath(AppKernel::appRootDir()));
        $this->container->setParameter('kernel.cache_dir', realpath(AppKernel::appCacheDir()));
        $this->container->setParameter('kernel.logs_dir', realpath(AppKernel::appLogsDir()));
    }

    public static function run()
    {
        (new self())->bootstrap();
    }

    private function bootstrap(): void
    {
        // TODO: setup cache for annotations, di container, config, etc.
        // TODO: register session in di container

        $request = $this->get('request');

        $kernel = $this->createKernel();

        try {
            $response = $kernel->handle($request);
        } catch (ResourceNotFoundException $e) {
            $response = new Response(null, 404);
//        } catch (\Exception $e) {
//            $response = new Response(null, 500);
        }
        $response->send();

        $kernel->terminate($request, $response);
    }

    private function registerAnnotations()
    {
        AnnotationRegistry::registerLoader(array(require self::COMPOSER_AUTOLOADER_PATH, 'loadClass'));
    }

    /**
     * @return HttpKernel
     * @throws \Exception
     * @throws \InvalidArgumentException
     */
    private function createKernel(): HttpKernel
    {
        $requestStack = $this->get('request_stack');

        $context = $this->get('request_context');
        $matcher = new UrlMatcher($this->routes(), $context);

        $dispatcher = new EventDispatcher();
        $dispatcher->addSubscriber(new RouterListener($matcher, $requestStack));
        // TODO: add ExceptionListener for MethodNotAllowed/NotFound/... + base HttpException

        $controllerResolver = new ContainerAwareControllerResolver(null, $this->container);
        $argumentResolver = new ArgumentResolver();

        return new AppKernel($dispatcher, $controllerResolver, $requestStack, $argumentResolver);
    }

    /**
     * @return \Symfony\Component\Routing\RouteCollection
     * @throws \InvalidArgumentException
     */
    private function routes(): RouteCollection
    {
        $routesAnnotationsLoader = new AnnotationDirectoryLoader(
            new FileLocator(),
            new AnnotationRouteControllerLoader(new AnnotationReader())
        );

        return $routesAnnotationsLoader->load(self::CONTROLLERS_PATH);
    }

    /**
     * @param string $serviceName
     * @return mixed
     * @throws \Exception
     * @throws \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     */
    private function get(string $serviceName)
    {
        return $this->container->get($serviceName);
    }
}
