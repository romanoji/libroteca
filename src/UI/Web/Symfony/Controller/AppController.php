<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\UI\Web\Symfony\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends Controller
{
    /**
     * @Route("/", methods={"GET"})
     */
    public function indexAction()
    {
        return new Response(sprintf("Symfony (%s).", Kernel::VERSION));
    }
}
