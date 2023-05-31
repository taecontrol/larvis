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

        $this->class = get_class($object);
        $this->parent = get_parent_class($object);
        $this->properties = $this->getProperties($reflection, $object, $filterProperties);

        return $this;
    }
}
