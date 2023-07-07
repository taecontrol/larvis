<?php

namespace Taecontrol\Larvis\Tests\Feature\Watchers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Taecontrol\Larvis\Tests\TestCase;
use Taecontrol\Larvis\Watchers\QueryWatcher;
use Illuminate\Foundation\Testing\RefreshDatabase;

class QueryWatcherTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        config()->set('larvis.krater.url', 'http://localhost:58673');
        config()->set('larvis.krater.api.messages', '/api/queries');
    }

    /** @test */
    public function it_check_if_queries_handler_post_query_data(): void
    {
        Http::fake([
            'http://localhost:58673/*' => Http::response([], 200, []),
        ]);

        app(QueryWatcher::class)->enable();

        DB::table('users')->get('id');

        $data = [
            'sql' => 'select "id" from "users"',
            'bindings' => [],
            'connection_name' => 'sqlite',
        ];

        Http::assertSent(function (Request $request) use ($data) {
            $this->assertIsFloat($request['query']['time']);

            $dataRequest = [
                'sql' => $request['query']['sql'],
                'bindings' => json_decode($request['query']['bindings']),
                'connection_name' => $request['query']['connection_name'],
            ];

            return $dataRequest === $data;
        });

        app(QueryWatcher::class)->disable();
    }
}
