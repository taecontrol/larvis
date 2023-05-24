<?php

namespace Taecontrol\Larvis\Tests\Feature;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Taecontrol\Larvis\Tests\TestCase;
use Taecontrol\Larvis\Watchers\RequestWatcher;
use Taecontrol\Larvis\ValueObjects\Data\AppData;
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

            /** @var AppData */
            $appData = AppData::fromArray($request['app']);

            $isRequestDataPresent = $request['request'] &&
            $request['request']['attributes'] === '[]' &&
            $request['request']['request_body'] === '""' &&
            $request['request']['files'] === '[]' &&
            $request['request']['headers'] &&
            $request['request']['content'] === '' &&
            $request['request']['server'] &&
            $request['request']['request_uri'] === '/test' &&
            $request['request']['base_url'] === '' &&
            $request['request']['method'] === 'GET' &&
            $request['request']['session'] === '[]' &&
            $request['request']['format'] === 'html' &&
            $request['request']['locale'] === 'en';

            $this->assertJson($request['request']['headers']);
            $this->assertJson($request['request']['server']);
            $this->assertJson($request['request']['response']);

            $response = json_decode($request['request']['response']);

            $isResponseDataPresent = $response->status === 200 &&
            $response->headers &&
            $response->content === 'ok' &&
            $response->version === '1.1' &&
            $response->original === 'ok';

            $isAppDataPresent = $appData->name === env('APP_NAME') &&
            $appData->framework === 'Laravel' &&
            $appData->frameworkVersion === app()->version() &&
            $appData->language === 'PHP' &&
            $appData->languageVersion === PHP_VERSION;

            return $isAppDataPresent && $isRequestDataPresent && $isResponseDataPresent;
        });

        app(RequestWatcher::class)->disable();
    }
}
