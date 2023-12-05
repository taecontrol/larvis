<?php

namespace Taecontrol\Larvis\Commands;

use Illuminate\Console\Command;
use Taecontrol\Larvis\Exceptions\CpuHealthException;
use Taecontrol\Larvis\Exceptions\DiskHealthException;
use Taecontrol\Larvis\Exceptions\MemoryHealthException;
use Taecontrol\Larvis\Larvis;

class CheckHardwareHealthCommand extends Command
{
    protected $signature = 'check:hardware';

    protected $description = 'check Cpu Memory and Disk space';


    public function getHardDiskUsage()
    {
        $result = false;
        $freeDiskSpace = false;
        $totalDiskSpace = false;

        if(function_exists('disk_free_space') && function_exists('disk_total_space')) {
            $freeDiskSpace = round((disk_free_space('/') / pow(1024, 3)), 2);
            $totalDiskSpace = round((disk_total_space('/') / pow(1024, 3)), 2);
        }

        $result = [
            "freeDiskSpace" => $freeDiskSpace,
            "totalDiskSpace" => $totalDiskSpace,
        ];

        if (!$result) {
            throw DiskHealthException::class;
        }

        return $result;
    }

    public function getMemoryUsage()
    {
        $result = false;

        if (function_exists('exec')) {
            $memory = shell_exec(" free | grep Mem | awk '{print $3/$2 * 100}' ");
            //$result = $memory;
            $result = round((float)$memory, 2);  //correguir

        }

        if (!$result) {
            throw MemoryHealthException::class;
        }

        return $result;

    }

    public function getCpuLoadUsage()
    {
        $result = false;

        if (function_exists('sys_getloadavg')) {
            $result = sys_getloadavg();
        }

        if (!$result) {
            throw CpuHealthException::class;
        }


        $result = array_map(fn ($n) => round($n, 2), $result);

        return $result[1]; //los ultimos 5 min
    }


    public function handle()
    {
        /** @var Larvis */
        //$larvis = app(Larvis::class);

        $test = $this->getCpuLoadUsage();
        $memory = $this->getMemoryUsage();
        $disk = $this->getHardDiskUsage();

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

