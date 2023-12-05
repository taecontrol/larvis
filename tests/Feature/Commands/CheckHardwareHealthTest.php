<?php

namespace Taecontrol\Larvis\Tests\Feature\Commands;

use Taecontrol\Larvis\Tests\TestCase;

class CheckHardwareHealthTest extends TestCase
{
    public function test_console_command(): void
    {
        $this->artisan('check:hardware')->assertExitCode(0);
    }
}
