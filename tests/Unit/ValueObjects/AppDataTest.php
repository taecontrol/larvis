<?php

namespace Taecontrol\Larvis\Tests\Unit\ValueObjects;

use Taecontrol\Larvis\Tests\TestCase;
use Taecontrol\Larvis\ValueObjects\Data\AppData;

class AppDataTest extends TestCase
{
    /** @test */
    public function it_validates_app_data_with_to_array_format()
    {
        $app = (new AppData('Laravel', '10', 'Laratest', 'PHP', '8.2'))->toArray();

        $this->assertEquals('Laravel', $app['framework']);
        $this->assertEquals('10', $app['framework_version']);
        $this->assertEquals('Laratest', $app['name']);
        $this->assertEquals('PHP', $app['language']);
        $this->assertEquals('8.2', $app['language_version']);
    }

    /** @test */
    public function it_validates_that_generate_creates_app_data()
    {
        $appData = AppData::generate();
        $this->assertInstanceOf(AppData::class, $appData);
        $this->assertNotEmpty($appData);
    }
}
