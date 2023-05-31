<?php

namespace Taecontrol\Larvis\Readers;

use ReflectionClass;

class DefaultReader extends Reader
{
    public function __construct(object $object)
    {
        $this->read($object);
    }

    public function read(object $object): self
    {
        $reflection = new ReflectionClass($object);

        $this->properties = $this->getProperties($reflection, $object);

        $this->class = get_class($object);

        $this->parent = get_parent_class($object);

        return $this;
    }
}
