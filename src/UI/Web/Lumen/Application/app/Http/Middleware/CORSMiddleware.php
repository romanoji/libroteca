<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Lumen\Http\Middleware;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CORSMiddleware
{
    /**
     * @param Request $request
     * @param \Closure $next
     * @return Response
     */
    public function handle(Request $request, \Closure $next)
    {
        /** @var Response $response */
        $response = $next($request);

        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }
}
