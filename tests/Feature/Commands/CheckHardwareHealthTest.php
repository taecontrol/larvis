<?php

namespace Taecontrol\Larvis\Tests\Feature\Commands;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Mockery;
use Mockery\MockInterface;
use Taecontrol\Larvis\Commands\CheckHardwareHealthCommand;
use Taecontrol\Larvis\Services\CheckService;
use Taecontrol\Larvis\Tests\TestCase;
use Taecontrol\Larvis\Services\HardwareService;
use Taecontrol\Larvis\Tests\Mock\Services\FakeHardwareService;

class CheckHardwareHealthTest extends TestCase
{
    public function  setUp(): void
    {
        parent::setUp();

        config()->set('larvis.moonguard.domain', 'http://moonguard.test');
        config()->set('larvis.moonguard.api.hardware', '/moonguard/api/hardware');

        $mock = Mockery::mock(HardwareService::class, function (MockInterface $mock) {
            $mock->shouldReceive('getHardwareData')
                 ->once()
                 ->andReturn([
                    'cpuLoad' => 10,
                    'memory' => 23,
                    'disk' => [
                        'freeSpace' => 79.7,
                        'totalSpace' => 181.7
                    ],
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
            $requestTotalDisk = $request['disk']['totalSpace'];
            return $requestTotalDisk === $data['disk']['totalSpace'];
        });
    }


    /** @test */
    public function it_asserts_that_data_has_correct_format(): void
    {
        config()->set('larvis.krater.enabled', false);

        $data = [
            'cpuLoad' => 10.0,
            'memory' => 23.0,
            'disk' => [
                'freeSpace' => 79.7,
                'totalSpace' => 181.7
            ],
        ];

        $hardwareService = new HardwareService();
        $checkService = new CheckService($hardwareService);

        $result = $checkService->getHardwareData();

       $this->assertTrue($result->toArray() === $data);
    }
}
