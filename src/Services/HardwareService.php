<?php

namespace Taecontrol\Larvis\Services;

use Taecontrol\Larvis\Exceptions\CpuHealthException;
use Taecontrol\Larvis\Exceptions\DiskHealthException;
use Taecontrol\Larvis\ValueObjects\Data\HardwareData;
use Taecontrol\Larvis\Exceptions\MemoryHealthException;

class HardwareService
{
    public function getHardwareData(): array
    {
        $cpuLoad = $this->getCpuLoadUsage();
        $memory = $this->getMemoryUsage();
        $disk = $this->getDiskUsage();

        $HardwareData = new HardwareData($cpuLoad, $memory, $disk);

        return $HardwareData->toArray();
    }

    public function getDiskUsage(): array
    {
        try {
            $freeSpace = false;
            $totalSpace = false;

            if (function_exists('disk_free_space') && function_exists('disk_total_space')) {
                $freeSpace = round((disk_free_space('/') / pow(1024, 3)), 1);
                $totalSpace = round((disk_total_space('/') / pow(1024, 3)), 1);
            }

            $result = [
                'freeSpace' => $freeSpace,
                'totalSpace' => $totalSpace,
            ];

            return $result;

        } catch (DiskHealthException $e) {
            throw $e->make();
        }
    }

    public function getMemoryUsage(): float
    {
        try {

            if (function_exists('exec')) {
                $memory = shell_exec(" free | grep Mem | awk '{print $3/$2 * 100}' ");
                $result = round((float) $memory);
            }

            return $result;

        } catch(MemoryHealthException $e) {
            throw $e->make();
        }
    }

    public function getCpuLoadUsage(): float
    {
        try {
            $result = false;

            if (function_exists('sys_getloadavg')) {
                $result = sys_getloadavg();
            }

            $result = array_map(fn ($n) => round($n * 100), $result);

            return $result[1];

        } catch(CpuHealthException $e) {
            throw $e->make();
        }
    }
}
