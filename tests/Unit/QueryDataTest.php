<?php

namespace Taecontrol\Larvis\Tests\Unit;

use Illuminate\Database\Events\QueryExecuted;
use Taecontrol\Larvis\Tests\TestCase;
use Taecontrol\Larvis\ValueObjects\QueryData;

class QueryDataTest extends TestCase
{
    /** @test */
    public function it_validates_Query_data_with_debug_format()
    {
        $query = new QueryExecuted('test Query', [], 2.98, 'test connection');
        $queryData = QueryData::from($query)->debugFormat();

        $this->assertIsArray($queryData);
        $this->assertNotEmpty($queryData);

        $this->assertIsString($queryData['sql'], 'test query');
        $this->assertIsArray($queryData['bindings']);
        $this->assertIsFloat($queryData['time'], 2.98);
        $this->assertIsString($queryData['connection_name'], 'test connection');

    }

    /** @test */
    public function it_validates_that_an_array_is_returned_from_to_array_method()
    {
        $query = new QueryExecuted('test Query', [], 2.98, 'test connection');
        $queryData = QueryData::from($query)->debugFormat();

        $this->assertIsArray($queryData);
        $this->assertNotEmpty($queryData);
    }
}
