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
        $freeSpace = false;
        $totalSpace = false;

        if(function_exists('disk_free_space') && function_exists('disk_total_space')) {
            $freeSpace = round((disk_free_space('/') / pow(1024, 3)), 1);
            $totalSpace = round((disk_total_space('/') / pow(1024, 3)), 1);
        }

        $result = [
            "freeSpace" => $freeSpace,
            "totalSpace" => $totalSpace,
        ];

        if (!$result) {

            throw DiskHealthException::make();
        }

        return $result;
    }

    public static function getMemoryUsage()
    {
        $result = false;
 
        if (function_exists('exec')) {
            $memory = shell_exec(" free | grep Mem | awk '{print $3/$2 * 100}' ");
            $result = round((float) $memory);
        }

        if (!$result) {
            throw MemoryHealthException::make();
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
            throw CpuHealthException::make();
        }

        $result = array_map(fn ($n) => round($n * 100), $result);

        return $result[1];
    }
}
