<?php

namespace Taecontrol\Larvis\Readers;

use ReflectionClass;

class ModelReader extends Reader
{
    public function __construct(object $object)
    {
        $this->data = $this->read($object);
    }

    public function read(object $object): array
    {
        $reflection = new ReflectionClass($object);

        $filterProperties = [
            'connection',
            'table',
            'primaryKey',
            'keyType',
            'incrementing',
            'with',
            'withCount',
            'preventsLazyLoading',
            'perPage',
            'exists',
            'hidden',
            'attributes',
            'original',
            'changes',
            'casts',
            'dates',
            'dateFormat',
            'appends',
            'relations',
            'touches',
            'timestamps',
            'visible',
            'fillable',
            'guarded',
            'rememberTokenName',
            'accessToken',
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
