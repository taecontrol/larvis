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

        $url = config('larvis.krater.url') . config('larvis.krater.api.messages');
        $data =  $hardwareData->toArray();
        $larvis->send($url, $data);

    }
}

