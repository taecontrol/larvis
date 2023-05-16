<?php

namespace Taecontrol\Larvis\Readers;

use ReflectionClass;

class CollectionReader extends Reader
{
    public function __construct(object $data)
    {
        $this->data = $this->read($data);
    }

    public function read(object $object): array
    {
        $reflection = new ReflectionClass($object);

        $filterProperties = [
            'items',
            'escapeWhenCastingToString',
        ];

        $properties = $this->getProperties($reflection, $object, $filterProperties);

        $constants = $reflection->getConstants();

        return [
            'properties' => $properties,
            'constants' => $constants,
            'class' => get_class($object),
            'parent' => get_parent_class($object),
        ];
    }
}
