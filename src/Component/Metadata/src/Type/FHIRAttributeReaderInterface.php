<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Type;

/**
 * Reads PHP attributes off generated model classes and their properties.
 *
 * Every method returns instantiated attribute objects or scalars — never a `\ReflectionAttribute`,
 * a `\ReflectionClass` or a `\ReflectionProperty`. Handing a handle back to a consuming component
 * would leave that component reflecting through a proxy, which is the coupling this interface
 * exists to remove.
 *
 * The three reads are deliberately separate because their inheritance semantics differ, and
 * conflating them silently changes what a validator enforces:
 *
 * - {@see self::classAttributes()} reads the concrete class only. PHP does not inherit class
 *   attributes, so this matches a bare `(new \ReflectionClass($x))->getAttributes(...)`.
 * - {@see self::propertyAttributes()} resolves through the property's *declaring* class, which is
 *   what makes a property-attribute check profile-safe.
 * - {@see self::declaresInHierarchy()} walks the parent chain, and is the only one that does.
 */
interface FHIRAttributeReaderInterface
{
    /**
     * Attributes of one class, declared on that class itself.
     *
     * Class attributes are not inherited in PHP, and this method does not simulate inheritance. A
     * profile subclass that declares no attribute of its own answers with an empty list even when
     * its parent carries one — use {@see self::declaresInHierarchy()} when the chain matters.
     *
     * @template T of object
     *
     * @param object|string   $subject        Instance or class name to read
     * @param class-string<T> $attributeClass Attribute to look for
     *
     * @return list<T> Instantiated attributes in declaration order; empty when the class is unknown
     */
    public function classAttributes(object|string $subject, string $attributeClass): array;

    /**
     * Attributes of one property, resolved through the class that declares it.
     *
     * PHP resolves an inherited property to the same `\ReflectionProperty` whether it is reached
     * through the child or the parent, so a profile subclass reads the attributes its parent
     * declared. That is why a property-attribute check needs no profile-awareness of its own.
     *
     * @template T of object
     *
     * @param object|string   $subject        Instance or class name owning the property
     * @param string          $property       Property name
     * @param class-string<T> $attributeClass Attribute to look for
     *
     * @return list<T> Instantiated attributes in declaration order; empty when the class or property is unknown
     */
    public function propertyAttributes(object|string $subject, string $property, string $attributeClass): array;

    /**
     * Whether the class or any of its ancestors declares the attribute.
     *
     * Answers presence only. A caller needing the attribute's arguments should walk with
     * {@see self::classAttributes()} instead, so that it is explicit about which class in the chain
     * the values came from.
     *
     * @param object|string        $subject        Instance or class name to read
     * @param class-string<object> $attributeClass Attribute to look for
     */
    public function declaresInHierarchy(object|string $subject, string $attributeClass): bool;

    /**
     * Every instance of an attribute declared anywhere in the class hierarchy, most-derived first.
     *
     * Distinct from {@see self::classAttributes()}, which reads one class and stops. A repeatable
     * attribute that a profile inherits rather than re-declares is invisible to that method, and the
     * consequence is silence rather than an error: a derived profile whose parent carries the
     * declaration behaves as though the declaration were absent.
     *
     * @template T of object
     *
     * @param object|string   $subject        Instance or class name to read
     * @param class-string<T> $attributeClass Attribute to look for
     *
     * @return list<T> Declaration order within each class, walking from the subject upwards
     */
    public function classAttributesInHierarchy(object|string $subject, string $attributeClass): array;

    /**
     * Whether the name refers to a loadable backed enum.
     *
     * False for a non-existent class, a plain class, and a pure (non-backed) enum. Folds the
     * existence, enum and backing checks into one question so callers do not re-derive them.
     *
     * @param string $candidate Fully-qualified name to test
     *
     * @phpstan-assert-if-true class-string<\BackedEnum> $candidate
     */
    public function isBackedEnum(string $candidate): bool;
}
