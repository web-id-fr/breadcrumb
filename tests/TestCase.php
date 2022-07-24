<?php

namespace WebId\Breadcrumb\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use WebId\Breadcrumb\BreadcrumbServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [BreadcrumbServiceProvider::class];
    }
}
