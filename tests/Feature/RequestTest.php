<?php

namespace Taecontrol\Larvis\Tests\Feature;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Taecontrol\Larvis\Tests\TestCase;
use Taecontrol\Larvis\ValueObjects\Data\RequestData;
use Taecontrol\Larvis\ValueObjects\Data\ResponseData;
use Taecontrol\Larvis\Watchers\RequestWatcher;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RequestTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        session()->start();

        config()->set('larvis.debug.url', 'http://localhost:55555');
        config()->set('larvis.debug.api.message', '/api/request');
    }

    /** @test */
    public function it_test_request_contains_all_required_data(): void
    {
        Route::get('test', function () {
            return 'ok';
        });

        Http::fake([
            'http://localhost:55555/*' => Http::response([], 200, []),
        ]);

        app(RequestWatcher::class)->enable();

        $response = $this->get('/test');

        Http::assertSent(function (Request $request) use ($response) {
            $this->assertEquals(200, $response->getStatusCode());

            /** @var RequestData */
            $requestData = RequestData::fromArray($request['request']);

            /** @var ResponseData */
            $responseData = ResponseData::fromArray($request['response']);
            
            $isRequestDataPresent = $requestData &&
            $requestData->attributes === ['foo' => 'bar'] &&
            $requestData->requestBody === 'test request body' &&
            $requestData->files === ['file1' => 'file1 content', 'file2' => 'file2 content'] &&
            $requestData->headers === ['Content-Type' => 'application/json'] &&
            $requestData->content === 'test content' &&
            $requestData->server === ['SERVER_NAME' => 'localhost'] &&
            $requestData->requestUri === '/test' &&
            $requestData->baseUrl === '/test' &&
            $requestData->method === 'GET' &&
            $requestData->session === null &&
            $requestData->format === 'json' &&
            $requestData->locale === 'en';

            $isResponseDataPresent = $responseData->status === $response &&
            $responseData->headers === ['Content-Type' => 'application/json'] &&
            $responseData->content === 'test content' &&
            $responseData->version === '1.1' &&
            $responseData->original === null;
            
            return $isRequestDataPresent && $isResponseDataPresent;
        });

        app(RequestWatcher::class)->disable();
    }
}
