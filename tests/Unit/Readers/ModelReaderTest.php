<?php

namespace Taecontrol\Larvis\Tests\Unit\Readers;

use Taecontrol\Larvis\Tests\TestCase;
use Taecontrol\Larvis\Tests\Mock\Test;
use Taecontrol\Larvis\Readers\ModelReader;
use Taecontrol\Larvis\Tests\Mock\Models\User;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ModelReaderTest extends TestCase
{
    /** @test */
    public function it_read_a_model_object_properties()
    {
        $user = new User(['name' => 'Fake', 'email' => 'Fake@mail.com']);
        $modelReader = (new ModelReader($user));

        $expected = [
            'fillable' => $modelReader->properties['+fillable'],
            'casts' => $modelReader->properties['+casts'],
            'connection' => $modelReader->properties['#connection'],
            'hidden' => $modelReader->properties['+hidden'],
            'primaryKey' => $modelReader->properties['#primaryKey'],
            'table' => $modelReader->properties['#table'],
            'class' => $modelReader->class,
            'parent' => $modelReader->parent,
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
