<?php

namespace Taecontrol\Larvis\Readers;

use Reflection;
use ReflectionClass;
use ReflectionProperty;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Collection as DBCollection;

abstract class Reader implements Arrayable
{
    public array $properties = [];

    public string $class = '';

    public string $parent = '';

    abstract public function read(object $object): self;

    public function toArray(): array
    {
        $array = ['class' => $this->class];

        if ($this->parent !== '') {
            $array['parent'] = $this->parent;
        }

        $array['properties'] = $this->properties;

        return $array;
    }

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
        $properties = $this->getFilteredProperties($reflection, $filterProperties);

        foreach ($properties as $property) {
            $modifiers = $this->getModifierFromProperty($property, $object);
            $modifierSymbol = $this->getModifierSymbol($modifiers);
            $value = $this->getValueFromProperty($property, $object);

            $formattedProperties["{$modifierSymbol}{$property->name}"] = $value;
        }

        return $formattedProperties;
    }

    public function getValueFromProperty(ReflectionProperty $property, object $object): mixed
    {
        $value = $property->getValue($object);

        if (is_array($value)) {
            foreach ($value as $key => $itemValue) {
                if (is_object($itemValue)) {
                    $value[$key] = static::getReader($itemValue)->toArray();
                }
            }
        }

        return $value;
    }

    public function getModifierFromProperty(ReflectionProperty $property, object $object): string
    {
        return implode(' ', Reflection::getModifierNames($property->getModifiers()));
    }

    private function getModifierSymbol(string $modifiers): string
    {
        if (str_contains($modifiers, 'protected')) {
            return '#';
        }

        if (str_contains($modifiers, 'private')) {
            return '-';
        }

        if (str_contains($modifiers, 'public')) {
            return '+';
        }

        return '';
    }

    private function getFilteredProperties(ReflectionClass $reflection, array $filterProperties = []): Collection
    {
        $properties = collect($reflection->getProperties());

        if (! empty($filterProperties)) {
            $properties = $properties->filter(
                fn ($item) => in_array($item->name, $filterProperties)
            );
        }

        return $properties;
    }
}
