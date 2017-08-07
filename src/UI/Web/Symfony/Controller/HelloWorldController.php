<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\UI\Web\Symfony\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloWorldController extends Controller
{
    /**
     * @Route("/hello/{name}", name="hello_world")
     *
     * @param string $name
     * @return Response
     */
    public function indexAction(string $name)
    {
        return new Response("Hello $name!");
    }
}
