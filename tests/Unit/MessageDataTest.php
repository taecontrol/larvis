<?php

namespace Taecontrol\Larvis\Tests\Unit;

use Taecontrol\Larvis\Readers\Reader;
use Taecontrol\Larvis\Tests\TestCase;
use Taecontrol\Larvis\Tests\Mock\TestObject;
use Taecontrol\Larvis\ValueObjects\Backtrace;
use Taecontrol\Larvis\ValueObjects\Data\MessageData;

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
    public function it_cast_null_data_into_string()
    {
        $backtrace = Backtrace::from(
            debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2)[0]
        );

        $messageData = MessageData::from(null, $backtrace);

        $expected = [
            'data' => 'null',
            'kind' => 'NULL',
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

        $messageData = MessageData::from('hello', $backtrace);

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

    /** @test */
    public function it_cast_array_data_into_string()
    {
        $backtrace = Backtrace::from(
            debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2)[0]
        );

        $messageData = MessageData::from(['a', 'b', 'c'], $backtrace);

        $expected = [
            'data' => '["a","b","c"]',
            'kind' => 'array',
            'file' => $backtrace->file,
            'line' => $backtrace->line,
        ];

        $this->assertEquals($expected, $messageData->toArray());
    }

    /** @test */
    public function it_cast_a_resource_into_string()
    {
        $backtrace = Backtrace::from(
            debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2)[0]
        );

        $path = realpath(dirname(__FILE__) . '/..') . '/Mock/Test.txt';

        $data = fopen($path, 'r');

        $messageData = MessageData::from($data, $backtrace);

        $expected = [
            'data' => 'This is a file for test purposes.',
            'kind' => 'resource',
            'file' => $backtrace->file,
            'line' => $backtrace->line,
        ];

        $this->assertEquals($expected, $messageData->toArray());
    }

    /** @test */
    public function it_cast_an_object_data_into_string()
    {
        $backtrace = Backtrace::from(
            debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2)[0]
        );

        $testObject = new TestObject();

        $messageData = MessageData::from($testObject, $backtrace);

        $testObject = json_encode(Reader::getReader($testObject)->data);

        $expected = [
            'data' => $testObject,
            'kind' => 'object',
            'file' => $backtrace->file,
            'line' => $backtrace->line,
        ];

        $this->assertEquals($expected, $messageData->toArray());

        $testObject = json_decode($messageData->data);

        $this->assertEquals($testObject->properties->propA->value, 'Hi');
        $this->assertEquals($testObject->properties->propA->modifiers, 'private');
        $this->assertEquals($testObject->properties->propB->value, 'World');
        $this->assertEquals($testObject->properties->propB->modifiers, 'public');
        $this->assertEquals($testObject->properties->propC->value, 'Ok');
        $this->assertEquals($testObject->properties->propC->modifiers, 'protected');
        $this->assertEquals($testObject->constants->A, 'HELLO WORLD');
    }
}
