<?php

namespace Taecontrol\Larvis\Tests\Feature;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Taecontrol\Larvis\Tests\TestCase;
use Taecontrol\Larvis\Watchers\RequestWatcher;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
            dd($request);

            $this->assertEquals(200, $response->getStatusCode());

            return true;
        });

        app(RequestWatcher::class)->disable();
    }
}
