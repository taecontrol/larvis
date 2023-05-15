<?php

namespace Taecontrol\Larvis\Tests;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Taecontrol\Larvis\Watchers\QueryWatcher;
use Illuminate\Foundation\Testing\RefreshDatabase;

class QueryTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        config()->set('larvis.debug.url', 'http://localhost:55555');
        config()->set('larvis.debug.api.message', '/api/query');
    }

    /** @test */
    public function it_check_if_queries_handler_post_query_data(): void
    {
        Http::fake([
            'http://localhost:55555/*' => Http::response([], 200, []),
        ]);

        app(QueryWatcher::class)->enable();

        DB::table('users')->get('id');

        $data = [
            'sql' => 'select "id" from "users"',
            'bindings' => [],
            'connection_name' => 'sqlite',
        ];

        Http::assertSent(function (Request $request) use ($data) {
            $dataRequest = [
                'sql' => $request['query']['sql'],
                'bindings' => $request['query']['bindings'],
                'connection_name' => $request['query']['connection_name'],
            ];

            return $dataRequest === $data;
        });

        app(QueryWatcher::class)->disable();
    }
}
