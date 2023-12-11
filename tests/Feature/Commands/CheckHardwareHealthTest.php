<?php

namespace Taecontrol\Larvis\Tests\Feature\Commands;

use Mockery\MockInterface;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Taecontrol\Larvis\Tests\TestCase;
use Taecontrol\Larvis\Services\HardwareService;
use Taecontrol\Larvis\Commands\CheckHardwareHealthCommand;

class CheckHardwareHealthTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        config()->set('larvis.moonguard.domain', 'http://moonguard.test');
        config()->set('larvis.moonguard.api.hardware', '/moonguard/api/hardware');
    }

    /** @test */
    public function it_asserts_that_data_its_send_correctly(): void
    {
        config()->set('larvis.krater.enabled', false);

        $this->mock(HardwareService::class, function (MockInterface $mock) {
            $mock->shouldReceive('getHardwareData')
                ->once()
                ->andReturn([
                    'cpuLoad' => 10,
                    'memory' => 23,
                    'disk' => [
                        'freeSpace' => 79.7,
                        'totalSpace' => 181.7,
                    ],
                ]);
        });

        $data = [
            'cpuLoad' => 10,
            'memory' => 23,
            'disk' => [
                'freeSpace' => 79.7,
                'totalSpace' => 181.7,
            ],
            'api_token' => config('larvis.moonguard.site.api_token'),
        ];

        Http::fake(['https://moonguard.test/*' => Http::response([], 200, [])]);

        $command = app(CheckHardwareHealthCommand::class);
        $command->handle();

        Http::assertSent(function (Request $request) use ($data) {
            $requestMemory = $request['memory'];
            $requestCpuLoad = $request['cpuLoad'];
            $requestTotalDisk = $request['disk']['totalSpace'];
            $requestFreeDisk = $request['disk']['freeSpace'];

            return $requestCpuLoad == $data['cpuLoad'] &&
                   $requestMemory == $data['memory'] &&
                   $requestFreeDisk == $data['disk']['freeSpace'] &&
                   $requestTotalDisk == $data['disk']['totalSpace'];
        });
    }

    /** @test */
    public function it_asserts_that_data_has_correct_format(): void
    {
        config()->set('larvis.krater.enabled', false);

        $this->mock(HardwareService::class, function (MockInterface $mock) {
            $mock->shouldReceive('getHardwareData')
                ->once()
                ->andReturn([
                    'cpuLoad' => 10,
                    'memory' => 23,
                    'disk' => [
                        'freeSpace' => 79.7,
                        'totalSpace' => 181.7,
                    ],
                ]);
        });

        $data = [
            'cpuLoad' => 10,
            'memory' => 23,
            'disk' => [
                'freeSpace' => 79.7,
                'totalSpace' => 181.7,
            ],
        ];

        $hardwareService = app(HardwareService::class);

        $result = $hardwareService->getHardwareData();
        $this->assertTrue($result === $data);
    }
}
