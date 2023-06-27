<?php

namespace Taecontrol\Larvis\Readers;

use ReflectionClass;

class ModelReader extends Reader
{
    public function __construct(object $object)
    {
        $this->read($object);
    }

    public function read(object $object): self
    {
        $reflection = new ReflectionClass($object);

        $filterProperties = config('larvis.reader.model.props');

        $this->class = get_class($object);
        $this->parent = get_parent_class($object);
        $this->properties = $this->getProperties($reflection, $object, $filterProperties);

        return $this;
    }
}
