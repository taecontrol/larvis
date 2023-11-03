<?php

namespace Taecontrol\Larvis\Tests\Unit\ValueObjects;

use Illuminate\Support\Carbon;
use Taecontrol\Larvis\Tests\TestCase;
use Illuminate\Database\Events\QueryExecuted;
use Taecontrol\Larvis\ValueObjects\Data\QueryData;
use Illuminate\Database\Capsule\Manager as Capsule;

class QueryDataTest extends TestCase
{
    /** @test */
    public function it_validates_query_data_with_debug_format()
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

        $this->assertEquals($queryData['sql'], 'test Query');
        $this->assertEquals($queryData['database'], 'test_database');
        $this->assertIsString($queryData['bindings']);
        $this->assertEquals($queryData['time'], 2.98);
        $this->assertEquals($queryData['connection_name'], 'default');
        $this->assertIsString($queryData['queried_at']);
    }

    /** @test */
    public function it_validates_query_data_with_to_array()
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
        $array = QueryData::from($query)->toArray();

        $this->assertIsArray($array);
        $this->assertNotEmpty($array);

        $this->assertEquals($array['sql'], 'test Query');
        $this->assertEquals($array['database'], 'test_database');
        $this->assertIsArray($array['bindings']);
        $this->assertIsFloat($array['time'], 2.98);
        $this->assertEquals($array['connection_name'], 'default');
        $this->assertIsObject($array['queried_at']);
        $this->assertInstanceOf(Carbon::class, $array['queried_at']);
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

    /** @test */
    public function it_formats_the_bindings_into_a_sql_string()
    {
        $sql = 'INSERT INTO table (name, age, data, tag, is_alive, tag_in_string) VALUES (?, ?, ?, ?, ?, ?)';
        $bindings = ['John', 25, '{"key": "value"}', null, true, 'null'];

        $formattedSQL = QueryData::formatBindingsInSQL($sql, $bindings);

        $this->assertEquals(
            "INSERT INTO table (name, age, data, tag, is_alive, tag_in_string) VALUES ('John', 25, '{\"key\": \"value\"}', null, 1, 'null')",
            $formattedSQL
        );
    }

    /** @test */
    public function it_formats_a_complex_json_object_into_a_valid_sql_string()
    {
        $sql = 'INSERT INTO table (content) VALUES (?)';

        $bindings = [json_encode([
            'line_preview' => [
                '30' => '',
                '31' => '    return "user-created";',
                '32' => '});',
                '33' => '',
                '34' => "Route::get('/delete', function () {",
                '35' => '',
                '36' => '    larvis()->startQueryWatch();',
                '37' => '    $user = User::find(1);',
                '38' => '',
                '39' => '    $user->delete();',
                '40' => '    larvis()->stopQueryWatch();',
                '41' => '    return "user-deleted";',
            ],
        ])];

        $formattedSql = QueryData::formatBindingsInSQL($sql, $bindings);

        $expectedSql = "INSERT INTO table (content) VALUES ('{\"line_preview\":{\"30\":\"\",\"31\":\"    return \\\"user-created\\\";\",\"32\":\"});\",\"33\":\"\",\"34\":\"Route::get(''\\/delete'', function () {\",\"35\":\"\",\"36\":\"    larvis()->startQueryWatch();\",\"37\":\"    \$user = User::find(1);\",\"38\":\"\",\"39\":\"    \$user->delete();\",\"40\":\"    larvis()->stopQueryWatch();\",\"41\":\"    return \\\"user-deleted\\\";\"}}')";

        $this->assertEquals(
            $expectedSql,
            $formattedSql
        );
    }
}
