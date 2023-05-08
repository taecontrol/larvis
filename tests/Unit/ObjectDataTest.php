<?php

namespace Taecontrol\Larvis\Tests\Unit;

use Taecontrol\Larvis\Tests\TestCase;
use Taecontrol\Larvis\Tests\Mock\Test;
use Taecontrol\Larvis\Tests\Mock\Models\User;
use Taecontrol\Larvis\ValueObjects\ObjectData;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ObjectDataTest extends TestCase
{
    /** @test */
    public function it_read_a_model_object_properties()
    {
        $user = new User(['name' => 'Fake', 'email' => 'Fake@mail.com']);
        $objectData = json_decode(ObjectData::from($user)->data, true);

        $expected = [
            'fillable' => $objectData['properties']['fillable']['value'],
            'casts' => $objectData['properties']['casts']['value'],
            'connection' => $objectData['properties']['connection']['value'],
            'hidden' => $objectData['properties']['hidden']['value'],
            'primaryKey' => $objectData['properties']['primaryKey']['value'],
            'table' => $objectData['properties']['table']['value'],
            'class' => $objectData['class'],
            'parent' => $objectData['parent'],
        ];

        $actual = [
            'fillable' => $user->fillable,
            'casts' => $user->casts,
            'connection' => $user->connection,
            'hidden' => $user->hidden,
            'primaryKey' => 'id',
            'table' => $user->table,
            'class' => User::class,
            'parent' => Authenticatable::class,
        ];

        $this->assertEquals($expected, $actual);
    }
}
