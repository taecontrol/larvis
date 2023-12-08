<?php

namespace Taecontrol\Larvis\Tests\Feature\Commands;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Taecontrol\Larvis\Larvis;
use Taecontrol\Larvis\Tests\TestCase;

class CheckHardwareHealthTest extends TestCase
{
    public function  setUp(): void
    {
        parent::setUp();

        config()->set('larvis.moonguard.url', 'http://localhost:58673');
        config()->set('larvis.moonguard.api.hardware', '/moonguard/api/hardware');
    }

    public function test_console_command(): void
    {
        /** @var Larvis */
        app(Larvis::class);

        $data = [
            'cpuLoad' => 10.2,
            'memory' => 23,
            'disk' => [
                'freeSpace' => 79.8,
                'totalSpace' => 181.7
            ]
        ];

        Http::fake(['https://localhost:5873/*' => Http::response(null, 201, [])]);

        $this->artisan('check:hardware')->expectsOutput('data')->assertExitCode(0);

        Http::assertSent(function (Request $request) use ($data) {
            var_dump($request);

            return $request === $data['disk']['totalSpace'];
        });
    }
}
