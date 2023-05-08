<?php

namespace Taecontrol\Larvis\ValueObjects;

use Reflection;
use ReflectionClass;
use ReflectionProperty;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class ObjectData
{
    public string $data;

    public function __construct(public readonly object $object)
    {
        $this->data = $this->readObject($object);
    }

    public function readObject(object $object): string
    {
        $definition = [
            'class' => get_class($object),
            'parent' => get_parent_class($object),
        ];

        if ($object instanceof Model) {
            return json_encode(
                array_merge($this->readModelObject($object), $definition)
            );
        }

        return json_encode(
            array_merge($this->readAllObject($object), $definition)
        );
    }

    public function readModelObject(object $object): array
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
        ];
    }

    public function readAllObject(object $object): array
    {
        $reflection = new ReflectionClass($object);

        $properties = $this->getProperties($reflection, $object);
        $constants = $reflection->getConstants();

        return [
            'properties' => $properties,
            'constants' => $constants,
        ];
    }

    public static function from(object $object)
    {
        return new self($object);
    }

    private function getProperties(ReflectionClass $reflection, object $object, array $filterProperties = []): array
    {
        /** @var Collection<ReflectionProperty> */
        $properties = collect($reflection->getProperties());

        if (! empty($filterProperties)) {
            $properties = $properties->filter(
                fn ($item) => in_array($item->name, $filterProperties)
            );
        }

        $formattedProperties = [];

        foreach ($properties as $property) {
            $modifiers = implode(' ', Reflection::getModifierNames($property->getModifiers()));

            $formattedProperties[$property->name] = [
                'value' => $property->getValue($object),
                'modifiers' => $modifiers,
            ];
        }

        return $formattedProperties;
    }
}
