<?php

namespace Taecontrol\Larvis\Commands;

use Taecontrol\Larvis\Larvis;
use Illuminate\Console\Command;
use Taecontrol\Larvis\Services\HardwareService;

class CheckHardwareHealthCommand extends Command
{
    protected $signature = 'check:hardware';

    protected $description = 'Checks CPU load, RAM usage and Disk space';

    public function handle(): void
    {
        /** @var Larvis */
        $larvis = app(Larvis::class);

        $hardwareService = app(HardwareService::class);

        $hardwareData = $hardwareService->getHardwareData();

        $url = config('larvis.moonguard.domain') . config('larvis.moonguard.api.hardware');
        dump($hardwareData);
        $data = array_merge(
            $hardwareData,
            ['api_token' => config('larvis.moonguard.site.api_token')],
        );

        $larvis->send($url, $data);
    }
}
