<?php

namespace Taecontrol\Larvis\Tests;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
//use Taecontrol\Larvis\ValueObjects\QueryData;
//use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Taecontrol\Larvis\Tests\Mock\Models\User;
//use Taecontrol\Larvis\Watchers\QueryWatcher;
use Taecontrol\Larvis\Larvis;

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

        /** @var Larvis */
        app(Larvis::class);

        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
        ]);

        $this->get('/users/' . $user->id);

        $data = [
            'sql' => "select * from sqlite_master where type = 'table' and name = ?",
            'bindings' => [
                "migrations"
            ],
            'connection_name' => 'sqlite'
        ];

        Http::fake([
            'http://localhost:55555/*' => Http::response([], 200, []),
        ]);

        Http::assertSent(function (Request $request) use ($data) {

            dd($request['query']);
            return $request['query'] === $data;
        });

    }
}
