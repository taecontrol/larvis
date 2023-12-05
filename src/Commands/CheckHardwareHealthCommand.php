<?php

namespace Taecontrol\Larvis\Commands;

use Illuminate\Console\Command;
use Taecontrol\Larvis\Larvis;
use Taecontrol\Larvis\Services\HardwareService;

class CheckHardwareHealthCommand extends Command
{
    protected $signature = 'check:hardware';

    protected $description = 'check Cpu Memory and Disk space';

    public function handle()
    {
        /** @var Larvis */
        //$larvis = app(Larvis::class);

        $test =  HardwareService::getCpuLoadUsage();
        $memory = HardwareService::getMemoryUsage();
        $disk = HardwareService::getDiskUsage();

        $this->info('cpu load');
        var_dump($test);

        $this->info('memory');
        var_dump($memory);

        $this->info('hard disk');
        var_dump($disk);

        // send data to moonguard
        //$url = config('larvis.krater.url') . config('larvis.krater.api.messages');
        //$cpuData = null;//create value object of  hardware.
        //$memoryData = null; // create value oject of hardware.
        //$diskData = null; // create a value object of hardware.
        //$data = [
            //"cpu" => $cpuData,
            //"memory" => $memoryData,
            //"disk" => $diskData,
        //];

        //$larvis->send($url, $data);

    }
}

