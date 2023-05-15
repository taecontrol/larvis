<?php

namespace Taecontrol\Larvis\Tests;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Client\Request;
//use Taecontrol\Larvis\ValueObjects\QueryData;
//use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\Http;
//use Taecontrol\Larvis\Watchers\QueryWatcher;
use Taecontrol\Larvis\Watchers\QueryWatcher;
use Illuminate\Foundation\Testing\RefreshDatabase;

class QueryTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        config()->set('larvis.debug.url', 'http://localhost:55555');
        config()->set('larvis.debug.api.message', '/api/messages');
    }

    /** @test */
    public function it_check_if_queries_handler_post_query_data(): void
    {
        Http::fake([
            'http://localhost:55555/*' => Http::response(null, 201, []),
        ]);

        app(QueryWatcher::class)->enable();

        DB::table('users')->get('id');

        $data = [
            'sql' => "select * from sqlite_master where type = 'table' and name = ?",
            'bindings' => [
                'migrations',
            ],
            'connection_name' => 'sqlite',
        ];

        Http::assertSent(function (Request $request) use ($data) {
            dd($request);

            return true;
        });

        app(QueryWatcher::class)->disable();
    }
}
