<?php

namespace Taecontrol\Larvis\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Taecontrol\Larvis\Providers\LarvisServiceProvider;

class TestCase extends Orchestra
{
    protected $loadEnvironmentVariables = true;

    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            LarvisServiceProvider::class,
        ];
    }
}
