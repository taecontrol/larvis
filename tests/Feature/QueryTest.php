<?php

namespace Taecontrol\Larvis\Tests;

use Taecontrol\Larvis\Larvis;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Taecontrol\Larvis\ValueObjects\AppData;
use Taecontrol\Larvis\ValueObjects\QueryData;
use Illuminate\Database\Events\QueryExecuted;

class MessagesTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        config()->set('larvis.debug.url', 'http://localhost:55555');
        config()->set('larvis.debug.api.message', '/api/messages');
    }

    /** @test */
    public function it_check_if_queries_handler_post_query_data(): void
    {
        $query = new QueryExecuted('test Query', [], 2.98, 'test connection');
        $queryArray = QueryData::from($query)->toArray();

        $data = [
            'query' => $queryArray,
        ];

        Http::fake([
            'http://localhost:55555/*' => Http::response([], 200, []),
        ]);

        Http::assertSent(function (Request $request) use ($data) {
            /** @var QueryData */
            $queryData = QueryData::fromArray([
                $request['sql'],
                $request['bindings'],
                $request['time'],
                $request['connection_name']
            ]);

            return $queryData === $data['query'];
        });

    }

