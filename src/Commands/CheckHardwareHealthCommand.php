<?php

namespace Taecontrol\Larvis\Commands;

use Illuminate\Console\Command;
use Taecontrol\Larvis\Larvis;
use Taecontrol\Larvis\Services\HardwareService;
use Taecontrol\Larvis\Services\CheckService;

class CheckHardwareHealthCommand extends Command
{
    protected $signature = 'check:hardware';

    protected $description = 'check Cpu Memory and Disk space';

    public function handle()
    {
        /** @var Larvis */
        $larvis = app(Larvis::class);

        $hardwareService = new HardwareService();
        $checkService = new CheckService($hardwareService);

        $data = $checkService->getHardwareData();

        $url = config('larvis.moonguard.domain') . config('larvis.moonguard.api.hardware');

        $data = array_merge(
            $data->toArray(),
            ['api_token' => config('larvis.moonguard.site.api_token')],
        );
        $larvis->send($url, $data);
    }
}

