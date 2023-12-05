<?php

namespace Taecontrol\Larvis\Services;

use Taecontrol\Larvis\Exceptions\CpuHealthException;
use Taecontrol\Larvis\Exceptions\DiskHealthException;
use Taecontrol\Larvis\Exceptions\MemoryHealthException;

class HardwareService
{

    public static function getDiskUsage()
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

    public static function getMemoryUsage()
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

    public static function getCpuLoadUsage()
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
}
