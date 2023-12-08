<?php

namespace Taecontrol\Larvis\Commands;

use Illuminate\Console\Command;
use Taecontrol\Larvis\Larvis;
use Taecontrol\Larvis\Services\HardwareService;
use Taecontrol\Larvis\ValueObjects\Data\HardwareData;

class CheckHardwareHealthCommand extends Command
{
    protected $signature = 'check:hardware';

    protected $description = 'check Cpu Memory and Disk space';

    public function handle()
    {
        /** @var Larvis */
        $larvis = app(Larvis::class);

        $cpuLoad =  HardwareService::getCpuLoadUsage();
        $memory = HardwareService::getMemoryUsage();
        $disk = HardwareService::getDiskUsage();

        $hardwareData = new HardwareData($cpuLoad, $memory, $disk);

        $url = config('larvis.moonguard.domain') . config('larvis.moonguard.api.hardware');

        $data = array_merge(
            $hardwareData->toArray(),
            ['api_token' => config('larvis.moonguard.site.api_token')],
        );

        $larvis->send($url, $data);

    }
}

