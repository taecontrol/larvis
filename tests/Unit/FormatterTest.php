<?php

namespace Taecontrol\Larvis\Tests\Unit;

use Taecontrol\Larvis\Readers\Reader;
use Taecontrol\Larvis\Tests\TestCase;
use Taecontrol\Larvis\Support\Formatter;
use Taecontrol\Larvis\Tests\Mock\TestObject;

class FormatterTest extends TestCase
{
    /** @test */
    public function it_cast_boolean_data_into_string()
    {
        $formatter = new Formatter();

        $formatted = $formatter
            ->format(false)
            ->toJson();

        $this->assertEquals('false', $formatted);
        $this->assertTrue($formatter->kind === 'boolean');
    }

    /** @test */
    public function it_cast_int_data_into_string()
    {
        $formatter = new Formatter();
        $formatted = $formatter
            ->format(15)
            ->toJson();

        $this->assertEquals('15', $formatted);
        $this->assertTrue($formatter->kind === 'int');
    }

    /** @test */
    public function it_cast_null_data_into_string()
    {
        $formatter = new Formatter();
        $formatted = $formatter
            ->format(null)
            ->toJson();

        $this->assertEquals('null', $formatted);
        $this->assertTrue($formatter->kind === 'NULL');
    }

    /** @test */
    public function it_cast_string_data_into_string()
    {
        $formatter = new Formatter();
        $formatted = $formatter
            ->format('Larvis')
            ->toJson();

        $this->assertEquals('"Larvis"', $formatted);
        $this->assertTrue($formatter->kind === 'string');
    }

    /** @test */
    public function it_cast_double_data_into_string()
    {
        $formatter = new Formatter();
        $formatted = $formatter
            ->format(30.2)
            ->toJson();

        $this->assertEquals('30.2', $formatted);
        $this->assertTrue($formatter->kind === 'double');
    }

    /** @test */
    public function it_cast_array_data_into_string()
    {
        $formatter = new Formatter();
        $formatted = $formatter
            ->format(['a', 'b', 'c'])
            ->toJson();

        $expected = '["a","b","c"]';

        $this->assertEquals($expected, $formatted);
        $this->assertTrue($formatter->kind === 'array');
    }

    /** @test */
    public function it_cast_a_resource_into_string()
    {
        $path = realpath(dirname(__FILE__) . '/..') . '/Mock/Test.txt';

        $data = fopen($path, 'r');

        $formatter = new Formatter();
        $formatted = $formatter
            ->format($data)
            ->toJson();

        $this->assertEquals('"This is a file for test purposes."', $formatted);
        $this->assertEquals($formatter->kind, 'resource');
    }

    /** @test */
    public function it_cast_an_object_data_into_string()
    {
        $testObject = new TestObject();

        $formatter = new Formatter();

        $formatted = $formatter
            ->format($testObject)
            ->toJson();

        $readedObjectInJson = json_encode(
            Reader::getReader($testObject)->toArray()
        );

        $this->assertEquals($readedObjectInJson, $formatted);
        $this->assertJson($formatted);
        $this->assertEquals($formatter->kind, 'object');
    }

    /** @test */
    public function it_does_recursive_formatting_with_objects_inside_arrays()
    {
        $object1 = new TestObject();
        $object2 = new TestObject();

        $array = [$object1, $object2];

        $formatter = new Formatter();

        $object1Readed = Reader::getReader($object1)->toArray();
        $object2Readed = Reader::getReader($object2)->toArray();

        $arrayInJson = json_encode(
            [$object1Readed, $object2Readed]
        );

        $formatted = $formatter
            ->format($array)
            ->toJson();

        $this->assertJson($formatted);
        $this->assertEquals($arrayInJson, $formatted);
        $this->assertEquals($formatter->kind, 'array');
    }

    /** @test */
    public function it_formats_slashes_correctly()
    {
        $string = 'https://sigma.test/login';

        $json = (new Formatter())->format($string)->toJson();

        $this->assertEquals($json, '"https://sigma.test/login"');
    }
}
