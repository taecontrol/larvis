<?php

namespace Taecontrol\Larvis\Tests\Unit\ValueObjects;

use Taecontrol\Larvis\Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Taecontrol\Larvis\ValueObjects\Data\RequestData;

class RequestDataTest extends TestCase
{
    /** @test */
    public function it_validates_request_data_with_debug_format()
    {
        $response = new Response();
        $request = RequestData::from(request(), $response)->debugFormat();

        $this->assertIsArray($request);
        $this->assertNotEmpty($request);

        $this->assertIsString($request['attributes'], '[]');
        $this->assertIsString($request['body'], '""');
        $this->assertIsString($request['files'], '[]');

        $headers = json_decode($request['headers']);
        $this->assertIsObject($headers);
        $this->assertNotEmpty($headers);
        $this->assertIsString($headers->host[0], 'localhost');

        $this->assertIsString($request['content'], '');

        $server = json_decode($request['server']);
        $this->assertIsObject($server);
        $this->assertNotEmpty($server);

        $uri = json_decode($request['uri']);

        $this->assertIsString($uri->root, 'http://localhost');
        $this->assertIsString($uri->path, '/');
        $this->assertIsString($uri->host, 'localhost');
        $this->assertIsNumeric($uri->port, '80');

        $this->assertIsString($request['method'], 'GET');
        $this->assertIsString($request['session'], '[]');
        $this->assertIsString($request['format'], 'html');
        $this->assertIsString($request['locale'], 'en');

        $response = json_decode($request['response']);
        $this->assertIsObject($response);
        $this->assertIsNumeric($response->status, 200);
        $this->assertIsString($response->status_text, 'OK');
        $this->assertIsObject($response->headers);
        $this->assertIsString($response->content, 'HTML Response');
        $this->assertIsString($response->version, '1.0');
    }

    /** @test */
    public function it_validates_request_data_with_to_array_format()
    {
        $response = new Response();
        $request = RequestData::from(request(), $response)->toArray();

        $this->assertIsArray($request);
        $this->assertNotEmpty($request);

        $this->assertEquals($request['attributes'], []);
        $this->assertEquals($request['body'], '');
        $this->assertEquals($request['files'], []);

        $this->assertIsArray($request['headers']);
        $this->assertNotEmpty($request['headers']);
        $this->assertIsString($request['headers']['host'][0], 'localhost');

        $this->assertIsString($request['content'], '');

        $this->assertIsArray($request['server']);
        $this->assertNotEmpty($request['server']);

        $this->assertIsString($request['uri']['root'], 'http://localhost');
        $this->assertIsString($request['uri']['path'], '/');
        $this->assertIsString($request['uri']['host'], 'localhost');
        $this->assertIsNumeric($request['uri']['port'], '80');

        $this->assertIsString($request['method'], 'GET');
        $this->assertEquals($request['session'], []);
        $this->assertIsString($request['format'], 'html');
        $this->assertIsString($request['locale'], 'en');

        $this->assertIsArray($request['response']);
        $this->assertNotEmpty($request['response']);
        $this->assertIsNumeric($request['response']['status'], 200);
        $this->assertIsString($request['response']['status_text'], 'OK');
        $this->assertIsArray($request['response']['headers']);
        $this->assertIsString($request['response']['content'], 'HTML Response');
        $this->assertIsString($request['response']['version'], '1.0');
    }
}
