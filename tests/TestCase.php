<?php

namespace Taecontrol\Larvis\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Taecontrol\Larvis\Providers\LarvisServiceProvider;

class TestCase extends Orchestra
{
    public function getEnvironmentSetUp($app)
    {
        // config()->set('database.default', 'testing');

        /*
        $migration = include __DIR__.'/../database/migrations/create_larastats-wingman_table.php.stub';
        $migration->up();
        */
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