<?php

namespace Taecontrol\Larvis\Tests\Unit\Readers;

use Illuminate\Http\Request;
use Taecontrol\Larvis\Readers\Reader;
use Taecontrol\Larvis\Tests\Mock\TestEmptyObject;
use Taecontrol\Larvis\Tests\TestCase;
use Symfony\Component\HttpFoundation\Request as SymphonyRequest;

class DefaultReaderTest extends TestCase
{
    /** @test */
    public function it_can_read_a_request_object_through_a_default_reader()
    {
        $request = new Request();

        $reader = Reader::getReader($request);

        $this->assertEquals($reader->class, Request::class);
        $this->assertEquals($reader->parent, SymphonyRequest::class);
        $this->assertNotEmpty($reader->properties);
        $this->assertIsArray($reader->properties);
        $this->assertEquals($reader->properties['#json'], null);
        $this->assertEquals($reader->properties['#format'], null);
        $this->assertEquals($reader->properties['#session'], null);
        $this->assertEquals($reader->properties['#content'], null);
        $this->assertEquals($reader->properties['#requestUri'], null);
    }

    /** @test */
    public function it_can_read_an_empty_class()
    {
        $reader = Reader::getReader(new TestEmptyObject());

        $this->assertNotEmpty($reader);
        $this->assertNotNull($reader);
        $this->assertEquals($reader->properties, []);
        $this->assertEquals($reader->class, TestEmptyObject::class);
        $this->assertEquals($reader->parent, "");
    }
}
