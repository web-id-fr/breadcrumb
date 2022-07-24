<?php

namespace WebId\Breadcrumb\Http\Middleware;

use Closure;
use Illuminate\Container\Container;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Inertia\Inertia;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\Response;

class RegisterBreadcrumb
{
    public function __construct(private Container $app)
    {
    }

    public function handle(Request $request, Closure $next, string $class, string $method): Response
    {
        /** @var Route $currentRoute */
        $currentRoute = $request->route();

        /** @var array $params */
        $params = $currentRoute->parameters;

        try {
            /** @phpstan-ignore-next-line */
            $breadcrumb = $this->app->call([$this->app->make($class), $method], $params);
            Inertia::share('breadcrumb', $breadcrumb);
        } catch (ContainerExceptionInterface | NotFoundExceptionInterface $exception) {
            return $next($request);
        }
        return $next($request);
    }
}
