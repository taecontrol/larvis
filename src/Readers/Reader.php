<?php

namespace Taecontrol\Larvis\Readers;

use Reflection;
use ReflectionClass;
use ReflectionProperty;
use Illuminate\Support\Collection;

abstract class Reader
{
    public array $data;

    abstract public function read(object $object): array;

    public static function getReader(object $object): Reader
    {
        if ($object instanceof Model) {
            return new ModelReader($object);
        }

        if ($object instanceof Collection || $object instanceof DBCollection) {
            return new CollectionReader($object);
        }

        return new DefaultReader($object);
    }

    public function getProperties(ReflectionClass $reflection, object $object, array $filterProperties = []): array
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
