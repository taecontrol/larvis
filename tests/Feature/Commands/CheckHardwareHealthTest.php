<?php

namespace Taecontrol\Larvis\Tests\Feature\Commands;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Mockery\MockInterface;
use Taecontrol\Larvis\Services\HardwareService;
use Taecontrol\Larvis\Tests\TestCase;

class CheckHardwareHealthTest extends TestCase
{
    public function  setUp(): void
    {
        parent::setUp();

        config()->set('larvis.moonguard.domain', 'http://moonguard.test');
        config()->set('larvis.moonguard.api.hardware', '/moonguard/api/hardware');

        // mock hardware service
        $mock = $this->mock(HardwareService::class, function (MockInterface $mock) {
            $mock->shouldReceive('getCPULoadUsage')->once()->andReturn(10);
            $mock->shouldReceive('getMemoryUsage')->once()->andReturn(23);
            $mock->shouldReceive('getDiskUsage')->once()->andReturn([
                'freeSpace' => 79.7,
                'totalSpace' => 181.7
            ]);
        });

        app()->instance(HardwareService::class, $mock);
    }
    /** @test */
    public function it_asserts_that_data_its_send_correctly(): void
    {
        config()->set('larvis.krater.enabled', false);

        $data = [
            'cpuLoad' => 10,
            'memory' => 23,
            'disk' => [
                'freeSpace' => 79.7,
                'totalSpace' => 181.7
            ],
            'api_token' => config('larvis.moonguard.site.api_token'),
        ];

        Http::fake(['https://moonguard.test/*' => Http::response([], 200, [])]);

        $this->artisan('check:hardware');

        Http::assertSent(function (Request $request) use ($data) {
            //dd($request['cpuLoad']);
            //dd($request);
            $requestCpu = $request['disk']['freeSpace'];
            //dd($requestCpu);
            return $requestCpu === $data['disk']['freeSpace'];
        });
    }
}
