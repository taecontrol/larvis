<?php

namespace Taecontrol\Larvis\Tests\Unit\ValueObjects;

use Exception;
use Taecontrol\Larvis\Tests\TestCase;
use Taecontrol\Larvis\ValueObjects\Data\ExceptionData;

class ExceptionDataTest extends TestCase
{
    /** @test */
    public function it_validates_exception_data_with_debug_format()
    {
        $exception = new Exception('test exception');
        $exceptionData = ExceptionData::from($exception)->debugFormat();

        $this->assertIsArray($exceptionData);
        $this->assertNotEmpty($exceptionData);

        $this->assertIsString($exceptionData['message'], 'test exception');
        $this->assertIsString($exceptionData['kind'], 'Exception');
        $this->assertIsString($exceptionData['file'], __FILE__);
        $this->assertIsString($exceptionData['trace']);
        $this->assertIsInt($exceptionData['line'], 15);
        $this->assertIsString($exceptionData['request']);
        $this->assertIsString($exceptionData['thrown_at']);
    }

    /** @test */
    public function it_validates_that_debug_format_has_request_data()
    {
        $exception = new Exception('test exception');
        $exceptionData = ExceptionData::from($exception)->debugFormat();

        $this->assertIsArray($exceptionData);
        $this->assertNotEmpty($exceptionData);

        $request = json_decode($exceptionData['request']);

        $this->assertEquals($request->url, 'http://localhost');
        $this->assertEquals($request->params, []);
        $this->assertEquals($request->query, []);

        $this->assertObjectHasAttribute('headers', $request);
        $this->assertObjectHasAttribute('server', $request);
    }

    /** @test */
    public function it_validates_that_an_array_is_returned_from_to_array_method()
    {
        $exception = new Exception('test exception');
        $exceptionData = ExceptionData::from($exception)->debugFormat();

        $this->assertIsArray($exceptionData);
        $this->assertNotEmpty($exceptionData);
    }
}
