<?php

namespace Taecontrol\Larvis\Tests\Feature\Commands;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Mockery;
use Taecontrol\Larvis\Larvis;
use Taecontrol\Larvis\Services\HardwareService;
use Taecontrol\Larvis\Tests\TestCase;

class CheckHardwareHealthTest extends TestCase
{
    public function  setUp(): void
    {
        parent::setUp();

        config()->set('larvis.moonguard.url', 'http://localhost:58673');
        config()->set('larvis.moonguard.api.hardware', '/moonguard/api/hardware');

        // mock hardware service
        $hardwareServiceMock = Mockery::mock(HardwareService::class);
        $hardwareServiceMock->shouldReceive('getCPULoadUsage')->once()->andReturn(10.2);
        $hardwareServiceMock->shouldReceive('getMemoryUsage')->once()->andReturn(23);
        $hardwareServiceMock->shouldReceive('getDiskUsage')->once()->andReturn([
            'freeSpace' => 79.8,
            'totalSpace' => 181.7,
        ]);

        app()->instance(HardwareService::class, $hardwareServiceMock);
    }
    /** @test */
    public function it_asserts_that_data_its_send_correctly(): void
    {
        /** @var Larvis */
        app(Larvis::class);

        $data = [
            'cpuLoad' => 10.2,
            'memory' => 23,
            'disk' => [
                'freeSpace' => 79.8,
                'totalSpace' => 181.7
            ],
            'api_token' => config('larvis.moonguard.site.api_token'),
        ];

        Http::fake(['https://localhost:5873/*' => Http::response(null, 201, [])]);

        $this->artisan('check:hardware')->run();

        Http::assertSent(function (Request $request) use ($data) {
            dd($request);
            return $request === $data;
        });
    }
}
