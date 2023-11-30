<?php

namespace Taecontrol\Larvis\Commands;

use Illuminate\Console\Command;

class CheckHardwareHealthCommand extends Command
{
    protected $signature = 'check:hardware';

    protected $description = 'check Cpu Memory and Disk space';

    public function handle()
    {
        $test = 'hola bb';

        $this->info($test);
    }
}

