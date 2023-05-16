<?php

namespace Taecontrol\Larvis\Readers;

use ReflectionClass;

class DefaultReader extends Reader
{
    public function __construct(object $object)
    {
        $this->data = $this->read($object);
    }

    public function read(object $object): array
    {
        $reflection = new ReflectionClass($object);

        $properties = $this->getProperties($reflection, $object);

        $constants = $reflection->getConstants();

        return [
            'properties' => $properties,
            'constants' => $constants,
            'class' => get_class($object),
            'parent' => get_parent_class($object),
        ];
    }
}
