<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Type;

/**
 * Reads and builds generated model objects, so callers never hold a reflection handle themselves.
 *
 * This is the home for the questions that reflection is genuinely the only answer to: what type did
 * the generator declare for a property, what defaults does a constructor carry, and how do you build
 * an instance whose typed properties are safe to read. None of that is derivable from attributes --
 * `FhirProperty::$phpType` records the item class for *array* properties only, so for every
 * single-valued element the declared PHP type is the sole source of truth.
 *
 * Whole operations are exposed rather than reflection primitives. `instantiateWithDefaults()` is a
 * method here instead of a `setValue()` a caller drives, because handing back the pieces would put the
 * handle back in the caller and defeat the point.
 *
 * @author Ardenexal
 */
interface FHIRModelAccessorInterface
{
    /**
     * Public property names, in declaration order, including inherited and static ones.
     *
     * Order is load-bearing: XML element order follows it where no explicit ordering applies.
     *
     * @param object|string $subject An instance, or a class name; an unloadable name gives an empty list
     *
     * @return list<string> Property names as declared
     */
    public function publicPropertyNames(object|string $subject): array;

    /**
     * Whether the class declares or inherits a property of this name.
     *
     * @param object|string $subject  An instance or class name
     * @param string        $property Property name to look for
     *
     * @return bool True when the property exists
     */
    public function hasProperty(object|string $subject, string $property): bool;

    /**
     * The declared type of a property, builtin types included.
     *
     * @param object|string $subject  An instance or class name
     * @param string        $property Property name to inspect
     *
     * @return string|null The declared type name, or null when the property is untyped, absent, or a
     *                     union rather than a single named type
     */
    public function declaredTypeOf(object|string $subject, string $property): ?string;

    /**
     * The first non-builtin type a property declares, looking through a union.
     *
     * This is what tells a normalizer which wrapper class to hydrate a value into. Returning null
     * makes the caller fall back to the raw decoded value, so a wrong null is silent -- it produces a
     * bare scalar or array where a model object belonged, with no exception.
     *
     * @param object|string $subject  An instance or class name
     * @param string        $property Property name to inspect
     *
     * @return string|null The class name, or null when the property declares only builtin types
     */
    public function declaredClassOf(object|string $subject, string $property): ?string;

    /**
     * Constructor default values across the whole hierarchy, most-derived winning.
     *
     * Walks root-first so a re-declared parameter keeps the most-derived class's default.
     *
     * @param object|string $subject An instance or class name
     *
     * @return array<string, mixed> Parameter name to default value, for parameters that have one
     */
    public function constructorDefaults(object|string $subject): array;

    /**
     * Build an instance without calling its constructor, then fill typed slots so they are safe to read.
     *
     * Constructor defaults are applied where they exist, and a non-nullable array property with no
     * default is initialized to an empty array. Uninitialized typed properties throw on access rather
     * than returning null, which is why this exists at all.
     *
     * @param object|string $subject An instance or class name to build from
     *
     * @return object A new instance with no uninitialized typed property left readable-unsafe
     *
     * @throws \ReflectionException When the class cannot be reflected
     */
    public function instantiateWithDefaults(object|string $subject): object;

    /**
     * Build an instance by calling its constructor with every parameter's default, or null.
     *
     * Used where a class's constructor performs work that matters -- backbone elements in particular --
     * so bypassing it would produce a differently-shaped object.
     *
     * @param object|string $subject An instance or class name to build from
     *
     * @return object A new instance built through its constructor
     *
     * @throws \ReflectionException When the class cannot be reflected
     */
    public function instantiateWithConstructorDefaults(object|string $subject): object;

    /**
     * Read a property's value, or null when the typed slot was never assigned.
     *
     * A typed property that the deserializer never populated throws on direct access rather than
     * reading back as null, so every read of model state has to be guarded. This is the guarded read.
     *
     * @param object $object   Instance to read from
     * @param string $property Property name to read
     *
     * @return mixed The value, or null when the property is absent or uninitialised
     */
    public function readInitializedValue(object $object, string $property): mixed;

    /**
     * Copy a typed `value[x]` choice back onto the plain `value` property of an extension.
     *
     * Does nothing when the object has no `value` property or no populated `value*` sibling.
     *
     * @param object $object The extension instance to normalize in place
     */
    public function copyTypedExtensionValueBack(object $object): void;
}
