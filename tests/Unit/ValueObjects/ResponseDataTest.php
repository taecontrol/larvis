<?php

namespace Taecontrol\Larvis\Tests\Unit\ValueObjects;

use Taecontrol\Larvis\Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Taecontrol\Larvis\ValueObjects\Data\ResponseData;

class ResponseDataTest extends TestCase
{
    /** @test */
    public function it_validates_response_data_with_debug_format()
    {
        $response = ResponseData::from(new Response())->debugFormat();

        $this->assertIsString($response['status'], '200');
        $this->assertIsString($response['status_text'], 'OK');

        $headers = json_decode($response['headers']);
        $this->assertIsObject($headers);
        $this->assertNotEmpty($headers);

        $this->assertIsString($response['content'], 'HTML Response');
        $this->assertIsString($response['version'], '1.0');
    }

    /** @test */
    public function it_validates_response_data_with_to_array_format()
    {
        $response = ResponseData::from(new Response())->toArray();

        $this->assertIsNumeric($response['status'], '200');
        $this->assertIsString($response['status_text'], 'OK');

        $this->assertIsArray($response['headers']);
        $this->assertNotEmpty($response['headers']);

        $this->assertIsString($response['content'], 'HTML Response');
        $this->assertIsString($response['version'], '1.0');
    }
}
