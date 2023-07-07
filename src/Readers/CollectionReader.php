<?php

namespace Taecontrol\Larvis\Readers;

use ReflectionClass;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Collection as DBCollection;

class CollectionReader extends Reader
{
    public function __construct(Collection | DBCollection $collection)
    {
        $this->read($collection);
    }

    public function read(object $object): self
    {
        $reflection = new ReflectionClass($object);

        $filterProperties = config('larvis.readers.collection.props');

        $this->class = get_class($object);
        $this->parent = get_parent_class($object);
        $this->properties = $this->getProperties($reflection, $object, $filterProperties);

        return $this;
    }
}
