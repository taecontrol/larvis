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
        $larvis = app(Larvis::class);

        $hardwareService = app(HardwareService::class);

        $data = $hardwareService->getHardwareData();

        $url = config('larvis.moonguard.domain') . config('larvis.moonguard.api.hardware');

        $data = array_merge(
            $data,
            ['api_token' => config('larvis.moonguard.site.api_token')],
        );
        $larvis->send($url, $data);
    }
}

