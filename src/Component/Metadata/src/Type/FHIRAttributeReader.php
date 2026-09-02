<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Type;

/**
 * Reflection-backed {@see FHIRAttributeReaderInterface}.
 *
 * Reflection happens here rather than in the consuming components, which is the whole point: one
 * owner, behind an interface, inside Metadata.
 *
 * Every cache is keyed by class shape, which is fixed at code-generation time, so two instances can
 * never disagree and the caches never need invalidating. Instantiated attributes are shared rather
 * than rebuilt per call — the generated attributes are readonly value objects, so a caller cannot
 * mutate one another caller then reads.
 */
final class FHIRAttributeReader implements FHIRAttributeReaderInterface
{
    /** @var array<string, list<object>> keyed by `class::attribute` */
    private array $classCache = [];

    /** @var array<string, list<object>> keyed by `declaringClass::property::attribute` */
    private array $propertyCache = [];

    /** @var array<string, bool> keyed by `class::attribute` */
    private array $hierarchyCache = [];

    /** @var array<string, list<object>> keyed by `class::attribute` */
    private array $hierarchyAttributeCache = [];

    /** @var array<string, bool> */
    private array $backedEnumCache = [];

    /**
     * @template T of object
     *
     * @param class-string<T> $attributeClass
     *
     * @return list<T>
     */
    public function classAttributes(object|string $subject, string $attributeClass): array
    {
        $class = self::classOf($subject);

        if ($class === null) {
            return [];
        }

        $key = $class . '::' . $attributeClass;

        if (!isset($this->classCache[$key])) {
            $this->classCache[$key] = self::instantiate(
                (new \ReflectionClass($class))->getAttributes($attributeClass),
            );
        }

        return self::narrow($this->classCache[$key], $attributeClass);
    }

    /**
     * @template T of object
     *
     * @param class-string<T> $attributeClass
     *
     * @return list<T>
     */
    public function propertyAttributes(object|string $subject, string $property, string $attributeClass): array
    {
        $class = self::classOf($subject);

        if ($class === null) {
            return [];
        }

        try {
            $reflectionProperty = new \ReflectionProperty($class, $property);
        } catch (\ReflectionException) {
            return [];
        }

        // Keyed by the DECLARING class, not the concrete one: an inherited property resolves to the
        // same handle through parent and child, so a profile subclass must share the parent's entry
        // rather than build a second one that could drift from it.
        $key = $reflectionProperty->getDeclaringClass()->getName() . '::' . $property . '::' . $attributeClass;

        if (!isset($this->propertyCache[$key])) {
            $this->propertyCache[$key] = self::instantiate($reflectionProperty->getAttributes($attributeClass));
        }

        return self::narrow($this->propertyCache[$key], $attributeClass);
    }

    /**
     * @param class-string<object> $attributeClass
     */
    /**
     * {@inheritDoc}
     *
     * @template T of object
     *
     * @param class-string<T> $attributeClass
     *
     * @return list<T>
     */
    public function classAttributesInHierarchy(object|string $subject, string $attributeClass): array
    {
        $class = self::classOf($subject);

        if ($class === null) {
            return [];
        }

        $key = $class . '::' . $attributeClass;

        if (!isset($this->hierarchyAttributeCache[$key])) {
            $found = [];

            for ($cursor = $class; $cursor !== false; $cursor = get_parent_class($cursor)) {
                foreach (self::instantiate((new \ReflectionClass($cursor))->getAttributes($attributeClass)) as $attribute) {
                    $found[] = $attribute;
                }
            }

            $this->hierarchyAttributeCache[$key] = $found;
        }

        return self::narrow($this->hierarchyAttributeCache[$key], $attributeClass);
    }

    public function declaresInHierarchy(object|string $subject, string $attributeClass): bool
    {
        $class = self::classOf($subject);

        if ($class === null) {
            return false;
        }

        $key = $class . '::' . $attributeClass;

        if (!isset($this->hierarchyCache[$key])) {
            $found = false;

            for ($cursor = $class; $cursor !== false; $cursor = get_parent_class($cursor)) {
                if ((new \ReflectionClass($cursor))->getAttributes($attributeClass) !== []) {
                    $found = true;
                    break;
                }
            }

            $this->hierarchyCache[$key] = $found;
        }

        return $this->hierarchyCache[$key];
    }

    public function isBackedEnum(string $candidate): bool
    {
        if (!isset($this->backedEnumCache[$candidate])) {
            $this->backedEnumCache[$candidate] = enum_exists($candidate)
                && (new \ReflectionEnum($candidate))->isBacked();
        }

        return $this->backedEnumCache[$candidate];
    }

    /**
     * The class name behind an instance or a name, or null when nothing is loadable.
     *
     * A name that does not resolve returns null rather than throwing: callers ask about candidate
     * class names they built from a URL, and an unknown name is an ordinary "no" for them.
     *
     * @return class-string|null
     */
    private static function classOf(object|string $subject): ?string
    {
        if (is_object($subject)) {
            return $subject::class;
        }

        return class_exists($subject) || enum_exists($subject) ? $subject : null;
    }

    /**
     * @param list<\ReflectionAttribute<object>> $attributes
     *
     * @return list<object>
     */
    private static function instantiate(array $attributes): array
    {
        return array_map(
            static fn (\ReflectionAttribute $attribute): object => $attribute->newInstance(),
            $attributes,
        );
    }

    /**
     * Re-narrows a cached list to the attribute type the caller asked for.
     *
     * The caches are shared across attribute types, so they are typed as plain objects. Each entry
     * was built by `getAttributes($attributeClass)` and is already of that type; the `instanceof`
     * re-establishes that for the type checker by asking the question at runtime rather than
     * asserting the answer.
     *
     * @template T of object
     *
     * @param list<object>    $attributes
     * @param class-string<T> $attributeClass
     *
     * @return list<T>
     */
    private static function narrow(array $attributes, string $attributeClass): array
    {
        $narrowed = [];

        foreach ($attributes as $attribute) {
            if ($attribute instanceof $attributeClass) {
                $narrowed[] = $attribute;
            }
        }

        return $narrowed;
    }
}
