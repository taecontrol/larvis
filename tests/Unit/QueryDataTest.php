<?php

namespace Taecontrol\Larvis\Tests\Unit;

use Taecontrol\Larvis\Tests\TestCase;
use Illuminate\Database\Events\QueryExecuted;
use Taecontrol\Larvis\ValueObjects\Data\QueryData;
use Illuminate\Database\Capsule\Manager as Capsule;

class QueryDataTest extends TestCase
{
    /** @test */
    public function it_validates_Query_data_with_debug_format()
    {
        $capsule = new Capsule;

        $capsule->addConnection([
            'driver' => 'sqlite',
            'host' => 'localhost',
            'database' => 'test_database',
            'username' => 'test_username',
            'password' => 'test_password',
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
        ]);

        $connection = $capsule->getConnection();

        $query = new QueryExecuted('test Query', [], 2.98, $connection);
        $queryData = QueryData::from($query)->debugFormat();

        $this->assertIsArray($queryData);
        $this->assertNotEmpty($queryData);

        $this->assertIsString($queryData['sql'], 'test query');
        $this->assertIsString($queryData['bindings']);
        $this->assertIsFloat($queryData['time'], 2.98);
        $this->assertIsString($queryData['connection_name'], 'test connection');
        $this->assertIsString($queryData['queried_at']);
    }

    /** @test */
    public function it_validates_that_an_array_is_returned_from_to_array_method()
    {
        $capsule = new Capsule;

        $capsule->addConnection([
            'driver' => 'sqlite',
            'host' => 'localhost',
            'database' => 'test_database',
            'username' => 'test_username',
            'password' => 'test_password',
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
        ]);

        $connection = $capsule->getConnection();

        $query = new QueryExecuted('test Query', [], 2.98, $connection);
        $queryData = QueryData::from($query)->debugFormat();

        $this->assertIsArray($queryData);
        $this->assertNotEmpty($queryData);
    }
}
