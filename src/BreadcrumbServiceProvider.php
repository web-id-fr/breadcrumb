<?php

namespace WebId\Breadcrumb;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Spatie\LaravelPackageTools\Exceptions\InvalidPackage;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use WebId\Breadcrumb\Http\Middleware\RegisterBreadcrumb;

class BreadcrumbServiceProvider extends PackageServiceProvider
{
    /**
     * @throws InvalidPackage
     */
    public function register(): void
    {
        parent::register();

        $this->mergeConfigFrom(__DIR__.'/../config/breadcrumb.php', 'breadcrumb');

        \Illuminate\Routing\Route::macro('breadcrumb', function (array $params) {
            if (count($params) !== 2) {
                throw new \InvalidArgumentException(
                    'The argument must be an array containing the class and the method to be invoked'
                );
            }

            $class = $params[0];
            $method = $params[1];

            if (! class_exists($class) || get_parent_class($class) !== Breadcrumb::class) {
                throw new \InvalidArgumentException(
                    "Class {$class} must be an instance of WebId\Breadcrumb\Breadcrumb"
                );
            }

            if (! method_exists($class, $method)) {
                throw new \InvalidArgumentException("Method '{$method}' does not exists in {$class}");
            }

            /** @var Route $route */
            $route = $this;

            /** @var Router $router */
            $router = app()->make('router');

            $router->aliasMiddleware('breadcrumb', RegisterBreadcrumb::class);

            $route->middleware(sprintf('breadcrumb:%s,%s', $class, $method));
        });
    }

    public function boot(): void
    {
        parent::boot();

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/breadcrumb.php' => config_path('breadcrumb.php'),
            ], 'config');
        }
    }

    public function configurePackage(Package $package): void
    {
        $package
            ->name('breadcrumb')
            ->hasConfigFile();
    }
}
