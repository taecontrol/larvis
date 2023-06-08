<?php

namespace Taecontrol\Larvis\Tests\Unit\Readers;

use Illuminate\Support\Collection;
use Taecontrol\Larvis\Tests\TestCase;
use Taecontrol\Larvis\Tests\Mock\Test;
use Taecontrol\Larvis\Tests\Mock\TestObject;
use Taecontrol\Larvis\Tests\Mock\Models\User;
use Taecontrol\Larvis\Readers\CollectionReader;

class CollectionReaderTest extends TestCase
{
    /** @test */
    public function it_read_object_properties_from_a_collection_with_single_model()
    {
        $user = $user = new User(['name' => 'Fake', 'email' => 'Fake@mail.com']);
        $collection = collect($user);

        $collectionReaded = (new CollectionReader($collection))->toArray();

        $this->assertIsArray($collectionReaded);
        $this->assertEquals($collectionReaded['class'], Collection::class);
        $this->assertEquals($collectionReaded['properties']['#items']['name'], 'Fake');
        $this->assertEquals($collectionReaded['properties']['#items']['email'], 'Fake@mail.com');
    }

    /** @test */
    public function it_read_objects_properties_from_a_collection_with_an_array_of_objects()
    {
        $user = new TestObject('Jotaro', 'jojo@mail.com');
        $user2 = new TestObject('Dio', 'wry@mail.com');

        $collection = collect([$user, $user2]);

        $collectionReaded = (new CollectionReader($collection))->toArray();

        $this->assertIsArray($collectionReaded);
        $this->assertEquals($collectionReaded['class'], Collection::class);
        $this->assertEquals($collectionReaded['properties']['#items'][0]['class'], TestObject::class);
        $this->assertEquals($collectionReaded['properties']['#items'][1]['class'], TestObject::class);
        $this->assertEquals($collectionReaded['properties']['#items'][0]['properties']['+name'], 'Jotaro');
        $this->assertEquals($collectionReaded['properties']['#items'][1]['properties']['+name'], 'Dio');
    }

    /** @test */
    public function it_read_objects_properties_from_a_collection_with_an_array_with_models()
    {
        $user = new User(['name' => 'Fake', 'email' => 'Fake@mail.com']);
        $user2 = new User(['name' => 'Fake2', 'email' => 'Fake2@mail.com']);

        $collection = collect([$user, $user2]);

        $collectionReaded = (new CollectionReader($collection))->toArray();

        $this->assertIsArray($collectionReaded);
        $this->assertEquals($collectionReaded['class'], Collection::class);
        $this->assertEquals($collectionReaded['properties']['#items'][0]['class'], User::class);
        $this->assertEquals($collectionReaded['properties']['#items'][1]['class'], User::class);
        $this->assertEquals($collectionReaded['properties']['#items'][0]['properties']['#attributes']['name'], 'Fake');
        $this->assertEquals($collectionReaded['properties']['#items'][1]['properties']['#attributes']['name'], 'Fake2');
    }
}
