<?php

namespace Taecontrol\Larvis\Tests\Mock\Services;

use Taecontrol\Larvis\Interfaces\HardwareServiceInterface;
use Taecontrol\Larvis\ValueObjects\Data\HardwareData;

class FakeHardwareService implements HardwareServiceInterface
{
    public function getHardwareData()
    {
        $cpuLoad = $this->getCpuLoadUsage();
        $memory = $this->getMemoryUsage();
        $disk = $this->getDiskUsage();

        $hardwareData = new HardwareData($cpuLoad, $memory, $disk);

        return $hardwareData;
    }

    public function getDiskUsage()
    {
        $freeSpace = 79.7;
        $totalSpace = 181.7;

        $result = [
            "freeSpace" => $freeSpace,
            "totalSpace" => $totalSpace,
        ];

        return $result;
    }

    public function getMemoryUsage()
    {
        return 23;
    }

    public function getCpuLoadUsage()
    {
        return 10;
    }
}


