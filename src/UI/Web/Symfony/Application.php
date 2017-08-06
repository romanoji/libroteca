<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\UI\Web\Symfony;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\{
    Request, RequestStack, Response
};
use Symfony\Component\HttpKernel\{
    Controller\ArgumentResolver, Controller\ControllerResolver, EventListener\RouterListener, HttpKernel
};
use Symfony\Component\Routing\{
    Matcher\UrlMatcher, RequestContext, Route, RouteCollection
};

class Application
{
    public static function run() : void
    {
        // TODO: replace with routes config (+ controllers)
        $routes = new RouteCollection();
        $routes->add('hello', new Route('/hello/{name}', [
            '_controller' => function (Request $request) {
                return new Response(
                    sprintf("Hello %s", $request->get('name'))
                );
            }]
        ));

        // TODO: setup dependency injection, load config

        $request = Request::createFromGlobals();

        $matcher = new UrlMatcher($routes, new RequestContext());

        $dispatcher = new EventDispatcher();
        $dispatcher->addSubscriber(new RouterListener($matcher, new RequestStack()));

        $controllerResolver = new ControllerResolver();
        $argumentResolver = new ArgumentResolver();

        $kernel = new HttpKernel($dispatcher, $controllerResolver, new RequestStack(), $argumentResolver);

        $response = $kernel->handle($request);

        $response->send();

        $kernel->terminate($request, $response);
    }
}
