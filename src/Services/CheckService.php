<?php

namespace Taecontrol\Larvis\Services;

use Taecontrol\Larvis\Interfaces\HardwareServiceInterface;

class CheckService
{
    private $hardwareService;

    public function __construct(HardwareServiceInterface $hardwareService)
    {
        $this->hardwareService = $hardwareService;
    }

    public function getHardwareData()
    {
        return $this->hardwareService->getHardwareData();
    }
}
