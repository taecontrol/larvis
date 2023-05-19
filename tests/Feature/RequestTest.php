<?php

namespace Taecontrol\Larvis\Tests\Feature;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;
use Taecontrol\Larvis\Tests\TestCase;
use Taecontrol\Larvis\Watchers\RequestWatcher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Taecontrol\Larvis\ValueObjects\Data\RequestData;



class RequestTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        config()->set('larvis.debug.url', 'http://localhost:55555');
        config()->set('larvis.debug.api.message', '/api/request');
    }

    /** @test */
    public function test_request_contains_all_required_data(): void
    {
        Http::fake([
            'http://localhost:55555/*' => Http::response([], 200, []),
        ]);

        app(RequestWatcher::class)->enable();

        $request = new \Illuminate\Http\Request([
          'param1' => 'value1',
          'param2' => 'value2',
      ]);
        $request->setMethod('GET');
        $request->setRequestUri('/test');

        $response = $this->get('/test');

        $requestData = RequestData::from($request);

        Http::assertSent(function (Request $request) use ($requestData, $response) {
            $data = $request->json();

            $this->assertEquals(200, $response->getStatusCode());

            return $data['request'] === $requestData->toArray()
                && isset($responseData['status'])
                && isset($responseData['headers'])
                && isset($responseData['content']);
        });

        app(RequestWatcher::class)->disable();
    }
}