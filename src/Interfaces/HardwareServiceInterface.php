<?php

namespace Taecontrol\Larvis\Interfaces;

interface HardwareServiceInterface
{
    public function getHardwareData();
    public function getDiskUsage();
    public function getMemoryUsage();
    public function getCpuLoadUsage();
}
