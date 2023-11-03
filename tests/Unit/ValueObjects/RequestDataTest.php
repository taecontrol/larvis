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

        $this->assertEquals($request['attributes'], '[]');
        $this->assertEquals($request['body'], '""');
        $this->assertEquals($request['files'], '[]');

        $headers = json_decode($request['headers']);
        $this->assertIsObject($headers);
        $this->assertNotEmpty($headers);
        $this->assertEquals($headers->host[0], 'localhost');

        $this->assertEquals($request['content'], '');

        $server = json_decode($request['server']);
        $this->assertIsObject($server);
        $this->assertNotEmpty($server);

        $uri = json_decode($request['uri']);

        $this->assertEquals($uri->root, 'http://localhost');
        $this->assertEquals($uri->path, '/');
        $this->assertEquals($uri->host, 'localhost');
        $this->assertIsNumeric($uri->port, '80');

        $this->assertEquals($request['method'], 'GET');
        $this->assertEquals($request['session'], '[]');
        $this->assertEquals($request['format'], 'html');
        $this->assertEquals($request['locale'], 'en');

        $response = json_decode($request['response']);
        $this->assertIsObject($response);
        $this->assertIsNumeric($response->status, 200);
        $this->assertEquals($response->status_text, 'OK');
        $this->assertIsObject($response->headers);
        $this->assertEquals($response->content, 'HTML Response');
        $this->assertEquals($response->version, '1.0');
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
        $this->assertEquals($request['headers']['host'][0], 'localhost');

        $this->assertEquals($request['content'], '');

        $this->assertIsArray($request['server']);
        $this->assertNotEmpty($request['server']);

        $this->assertEquals($request['uri']['root'], 'http://localhost');
        $this->assertEquals($request['uri']['path'], '/');
        $this->assertEquals($request['uri']['host'], 'localhost');
        $this->assertIsNumeric($request['uri']['port'], '80');

        $this->assertEquals($request['method'], 'GET');
        $this->assertEquals($request['session'], []);
        $this->assertEquals($request['format'], 'html');
        $this->assertEquals($request['locale'], 'en');

        $this->assertIsArray($request['response']);
        $this->assertNotEmpty($request['response']);
        $this->assertIsNumeric($request['response']['status'], 200);
        $this->assertEquals($request['response']['status_text'], 'OK');
        $this->assertIsArray($request['response']['headers']);
        $this->assertEquals($request['response']['content'], 'HTML Response');
        $this->assertEquals($request['response']['version'], '1.0');
    }
}
