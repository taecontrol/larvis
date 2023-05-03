<?php

namespace Taecontrol\Larvis\Tests\Unit;

use Taecontrol\Larvis\Tests\TestCase;
use Taecontrol\Larvis\ValueObjects\Backtrace;
use Taecontrol\Larvis\ValueObjects\MessageData;

class MessageTest extends TestCase
{
    /** @test */
    public function it_cast_boolean_data_into_string()
    {
        $backtrace = Backtrace::from(
            debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2)[0]
        );

        $messageData = MessageData::from(false, $backtrace);

        $expected = [
            'data' => 'false',
            'kind' => 'boolean',
            'file' => $backtrace->file,
            'line' => $backtrace->line,
        ];

        $this->assertEquals($expected, $messageData->toArray());
    }

    /** @test */
    public function it_cast_integer_data_into_string()
    {
        $backtrace = Backtrace::from(
            debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2)[0]
        );

        $messageData = MessageData::from(15, $backtrace);

        $expected = [
            'data' => '15',
            'kind' => 'integer',
            'file' => $backtrace->file,
            'line' => $backtrace->line,
        ];

        $this->assertEquals($expected, $messageData->toArray());
    }

    /** @test */
    public function it_cast_string_data_into_string()
    {
        $backtrace = Backtrace::from(
            debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2)[0]
        );

        $messageData = MessageData::from("hello", $backtrace);

        $expected = [
          'data' => 'hello',
          'kind' => 'string',
          'file' => $backtrace->file,
          'line' => $backtrace->line,
        ];

        $this->assertEquals($expected, $messageData->toArray());
    }

    /** @test */
    public function it_cast_double_data_into_string()
    {
        $backtrace = Backtrace::from(
            debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2)[0]
        );

        $messageData = MessageData::from(30.2, $backtrace);

        $expected = [
          'data' => '30.2',
          'kind' => 'double',
          'file' => $backtrace->file,
          'line' => $backtrace->line,
        ];

        $this->assertEquals($expected, $messageData->toArray());
    }
}
