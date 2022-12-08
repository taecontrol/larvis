<?php

namespace Taecontrol\Larvis\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use  Taecontrol\Larvis\LarvisServiceProvider;

class TestCase extends Orchestra
{
    public function getEnvironmentSetUp($app)
    {

    }

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