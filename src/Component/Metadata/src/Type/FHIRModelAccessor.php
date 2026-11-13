<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Type;

/**
 * The only holder of reflection handles for generated model classes.
 *
 * Every method takes names or objects and returns names, values or objects. No `ReflectionClass` or
 * `ReflectionProperty` is ever returned or accepted, which is what keeps handles from crossing a
 * component boundary.
 *
 * Caches are static and keyed by class name. Generated model shape cannot change within a process, so
 * the lifetime is the request, and sharing across instances is the point rather than a leak.
 *
 * @author Ardenexal
 */
final class FHIRModelAccessor implements FHIRModelAccessorInterface
{
    /** @var array<class-string, \ReflectionClass<object>> */
    private static array $classCache = [];

    /** @var array<class-string, list<string>> */
    private static array $propertyNameCache = [];

    /** @var array<class-string, array<string, mixed>> */
    private static array $defaultsCache = [];

    /**
     * Handles for declared properties, by class and name.
     *
     * `ReflectionClass::getProperty()` builds a new handle on every call, and the validation tree
     * walks read every property of every node through here, so the handles are kept. Names a class
     * does not declare are not kept: the deserializer asks about every key in its input, so caching
     * misses would grow without bound in a long-running worker.
     *
     * @var array<class-string, array<string, \ReflectionProperty>>
     */
    private static array $propertyCache = [];

    /**
     * {@inheritDoc}
     */
    public function publicPropertyNames(object|string $subject): array
    {
        $class = self::classOf($subject);

        if ($class === null) {
            return [];
        }

        if (isset(self::$propertyNameCache[$class])) {
            return self::$propertyNameCache[$class];
        }

        $names = [];

        foreach (self::reflect($class)->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            $names[] = $property->getName();
        }

        return self::$propertyNameCache[$class] = $names;
    }

    /**
     * {@inheritDoc}
     */
    public function hasProperty(object|string $subject, string $property): bool
    {
        $class = self::classOf($subject);

        return $class !== null && self::property($class, $property) !== false;
    }

    /**
     * {@inheritDoc}
     */
    public function declaredTypeOf(object|string $subject, string $property): ?string
    {
        $type = self::propertyType($subject, $property);

        return $type instanceof \ReflectionNamedType ? $type->getName() : null;
    }

    /**
     * {@inheritDoc}
     */
    public function owningClassOf(object|string $subject, string $property): ?string
    {
        $class = self::classOf($subject);

        if ($class === null || !property_exists($class, $property)) {
            return null;
        }

        try {
            return (new \ReflectionProperty($class, $property))->getDeclaringClass()->getName();
        } catch (\ReflectionException) {
            return null;
        }
    }

    public function declaredClassOf(object|string $subject, string $property): ?string
    {
        $type = self::propertyType($subject, $property);

        if ($type instanceof \ReflectionNamedType && !$type->isBuiltin()) {
            return $type->getName();
        }

        if ($type instanceof \ReflectionUnionType) {
            foreach ($type->getTypes() as $member) {
                if ($member instanceof \ReflectionNamedType && !$member->isBuiltin() && $member->getName() !== 'null') {
                    return $member->getName();
                }
            }
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function constructorDefaults(object|string $subject): array
    {
        $class = self::classOf($subject);

        if ($class === null) {
            return [];
        }

        if (array_key_exists($class, self::$defaultsCache)) {
            return self::$defaultsCache[$class];
        }

        $hierarchy = [];

        for ($current = self::reflect($class); $current !== false; $current = $current->getParentClass()) {
            $hierarchy[] = $current;
        }

        $defaults = [];

        // Root first, so a re-declared parameter keeps the most-derived class's default.
        foreach (array_reverse($hierarchy) as $current) {
            $constructor = $current->getConstructor();

            if ($constructor === null || $constructor->getDeclaringClass()->getName() !== $current->getName()) {
                continue;
            }

            foreach ($constructor->getParameters() as $parameter) {
                if ($parameter->isDefaultValueAvailable()) {
                    $defaults[$parameter->getName()] = $parameter->getDefaultValue();
                }
            }
        }

        return self::$defaultsCache[$class] = $defaults;
    }

    /**
     * {@inheritDoc}
     */
    public function instantiateWithDefaults(object|string $subject): object
    {
        $class = self::classOf($subject);

        if ($class === null) {
            throw new \ReflectionException(sprintf('Cannot instantiate unknown class "%s".', is_string($subject) ? $subject : $subject::class));
        }

        $reflection = self::reflect($class);
        $object     = $reflection->newInstanceWithoutConstructor();
        $defaults   = $this->constructorDefaults($class);

        foreach ($reflection->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            if ($property->isStatic() || $property->isInitialized($object)) {
                continue;
            }

            $name = $property->getName();

            if (array_key_exists($name, $defaults)) {
                $property->setValue($object, $defaults[$name]);

                continue;
            }

            $type = $property->getType();

            if ($type instanceof \ReflectionNamedType && !$type->allowsNull() && $type->getName() === 'array') {
                $property->setValue($object, []);
            }
        }

        return $object;
    }

    /**
     * {@inheritDoc}
     */
    public function instantiateWithConstructorDefaults(object|string $subject): object
    {
        $class = self::classOf($subject);

        if ($class === null) {
            throw new \ReflectionException(sprintf('Cannot instantiate unknown class "%s".', is_string($subject) ? $subject : $subject::class));
        }

        $reflection  = self::reflect($class);
        $constructor = $reflection->getConstructor();

        if ($constructor === null) {
            return $reflection->newInstanceWithoutConstructor();
        }

        $args = [];

        foreach ($constructor->getParameters() as $parameter) {
            $args[] = $parameter->isDefaultValueAvailable() ? $parameter->getDefaultValue() : null;
        }

        return $reflection->newInstanceArgs($args);
    }

    /**
     * {@inheritDoc}
     */
    public function instantiateBare(object|string $subject): object
    {
        $class = self::classOf($subject);

        if ($class === null) {
            throw new \ReflectionException(sprintf('Cannot instantiate unknown class "%s".', is_string($subject) ? $subject : $subject::class));
        }

        return self::reflect($class)->newInstanceWithoutConstructor();
    }

    /**
     * {@inheritDoc}
     */
    public function readInitializedValue(object $object, string $property): mixed
    {
        $handle = self::property($object::class, $property);

        if ($handle === false) {
            return null;
        }

        return $handle->isInitialized($object) ? $handle->getValue($object) : null;
    }

    /**
     * {@inheritDoc}
     */
    public function isPropertyInitialized(object $object, string $property): bool
    {
        $handle = self::property($object::class, $property);

        return $handle !== false && $handle->isInitialized($object);
    }

    /**
     * {@inheritDoc}
     */
    public function initializedPublicValues(object $object): array
    {
        // Called from this class's scope, get_object_vars() sees public properties only, and it leaves
        // out typed properties that were never written: exactly the ones isPropertyInitialized()
        // rejects. It also leaves out statics, which are read through their handle instead.
        $vars   = get_object_vars($object);
        $values = [];

        foreach ($this->publicPropertyNames($object) as $name) {
            if (array_key_exists($name, $vars)) {
                $values[$name] = $vars[$name];

                continue;
            }

            $handle = self::property($object::class, $name);

            if ($handle !== false && $handle->isStatic() && $handle->isInitialized($object)) {
                $values[$name] = $handle->getValue($object);
            }
        }

        return $values;
    }

    /**
     * {@inheritDoc}
     */
    public function writeValue(object $object, string $property, mixed $value): void
    {
        $handle = self::property($object::class, $property);

        if ($handle === false) {
            return;
        }

        $handle->setValue($object, $value);
    }

    /**
     * {@inheritDoc}
     */
    public function copyTypedExtensionValueBack(object $object): void
    {
        $valueProperty = self::property($object::class, 'value');

        if ($valueProperty === false) {
            return;
        }

        foreach ($this->publicPropertyNames($object) as $name) {
            if ($name === 'value' || !str_starts_with($name, 'value')) {
                continue;
            }

            $property = self::property($object::class, $name);

            if ($property !== false
                && $property->isInitialized($object)
                && $property->getValue($object) !== null
            ) {
                $valueProperty->setValue($object, $property->getValue($object));

                break;
            }
        }
    }

    /**
     * The declared type of a property, as a reflection type or null.
     *
     * Private so the reflection type object never leaves this class.
     *
     * @param object|string $subject  An instance or class name
     * @param string        $property Property name to inspect
     *
     * @return \ReflectionType|null The declared type, or null when absent or untyped
     */
    private static function propertyType(object|string $subject, string $property): ?\ReflectionType
    {
        $class = self::classOf($subject);

        if ($class === null) {
            return null;
        }

        $handle = self::property($class, $property);

        return $handle === false ? null : $handle->getType();
    }

    /**
     * Resolve a subject to a loadable class name.
     *
     * @param object|string $subject An instance, class name, or a ReflectionClass to unwrap
     *
     * @return class-string|null The class name, or null when the string names no loadable class
     */
    private static function classOf(object|string $subject): ?string
    {
        // A ReflectionClass is an object, so without this it would satisfy the parameter type and the
        // accessor would happily reflect ReflectionClass itself -- producing "cannot set read-only
        // property ReflectionClass::$name" far from the call site. Unwrap it instead of guessing.
        if ($subject instanceof \ReflectionClass) {
            return $subject->getName();
        }

        if (is_object($subject)) {
            return $subject::class;
        }

        return class_exists($subject) ? $subject : null;
    }

    /**
     * Cached reflection for a class.
     *
     * @param class-string $class Class to reflect
     *
     * @return \ReflectionClass<object> The cached handle, never handed to a caller
     */
    private static function reflect(string $class): \ReflectionClass
    {
        return self::$classCache[$class] ??= new \ReflectionClass($class);
    }

    /**
     * Cached handle for one property of a class, or false (not cached) when the class has no such property.
     *
     * @param class-string $class    Class declaring or inheriting the property
     * @param string       $property Property name
     *
     * @return \ReflectionProperty|false The cached handle, never handed to a caller
     */
    private static function property(string $class, string $property): \ReflectionProperty|false
    {
        $cached = self::$propertyCache[$class][$property] ?? null;

        if ($cached !== null) {
            return $cached;
        }

        $reflection = self::reflect($class);

        if (!$reflection->hasProperty($property)) {
            return false;
        }

        return self::$propertyCache[$class][$property] = $reflection->getProperty($property);
    }
}
