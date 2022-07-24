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
        return $this->navigation
            ->add(
                strval(config('breadcrumb.breadcrumb_root.title')),
                route(strval(config('breadcrumb.breadcrumb_root.route_name')))
            );
    }

    public function render(array $breadcrumb): array
    {
        return array_map(
            fn ($breadcrumbElement) => BreadcrumbElement::make($breadcrumbElement)->toArray(),
            $breadcrumb
        );
    }
}
