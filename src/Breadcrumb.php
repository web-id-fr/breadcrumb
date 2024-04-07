<?php

namespace WebId\Breadcrumb;

use Spatie\Navigation\Navigation;

abstract class Breadcrumb
{
    public function __construct(private Navigation $navigation)
    {
    }

    protected function baseBreadcrumb(): Navigation
    {
        /** @var string|null $rootTitle */
        $rootTitle = config('breadcrumb.breadcrumb_root.title');
        /** @var string|null $routeName */
        $routeName = config('breadcrumb.breadcrumb_root.route_name');

        return $this->navigation
            ->add(
                __(strval($rootTitle)),
                route(strval($routeName))
            );
    }

    /**
     * @param array<array<string, string>> $breadcrumb
     * @return array<array<string, string>>
     */
    public function render(array $breadcrumb): array
    {
        return array_map(
            fn ($breadcrumbElement) => BreadcrumbElement::make($breadcrumbElement)->toArray(),
            $breadcrumb
        );
    }
}
