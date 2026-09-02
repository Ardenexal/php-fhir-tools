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
 * method here rather than a handle a caller drives, because handing back the pieces would put the
 * handle back in the caller and defeat the point. `writeValue()` is the deliberate exception: the
 * deserializer genuinely has to assign one named property at a time, and it takes a name and a value
 * so no handle crosses the boundary to do it.
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
     * The class that declares a property, which is not the class the property is typed as.
     *
     * Deliberately not named next to {@see self::declaredClassOf()}: that answers "what type does
     * this property hold", this answers "who declares it", and the two are unrelated. A `string`
     * property has no declared class at all and still has an owner.
     *
     * The distinction is load-bearing wherever an attribute on the owner decides how a property is
     * treated -- a primitive wrapper's own `value` slot is not the element a validator reports on,
     * while an `id` that wrapper inherits from Element still is.
     *
     * @param object|string $subject  An instance or class name
     * @param string        $property Property name to inspect
     *
     * @return string|null The declaring class, or null when the class or property is unknown
     */
    public function owningClassOf(object|string $subject, string $property): ?string;

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
     * An instance with nothing assigned at all, not even declared defaults.
     *
     * Deliberately distinct from {@see instantiateWithDefaults()}: this leaves every typed slot
     * unwritten, so reading one throws until it is assigned. Only for callers that populate what they
     * need and hand the object straight on -- an XHTML wrapper built to hold one string, say.
     *
     * @param object|string $subject An instance to copy the class of, or a class name
     *
     * @return object A bare instance of that class
     *
     * @throws \ReflectionException When the name does not resolve to a loadable class
     */
    public function instantiateBare(object|string $subject): object;

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
     * Whether a property's typed slot has ever been assigned.
     *
     * Distinct from reading it: `readInitializedValue()` answers null for an unwritten slot and for
     * one holding null, which is the right collapse almost everywhere. It is wrong where the two
     * outcomes differ -- a normalizer that omits unwritten properties but emits explicit nulls, or a
     * default that only applies to a slot nobody has touched. Those need this.
     *
     * @param object $object   Instance to probe
     * @param string $property Property name to probe
     *
     * @return bool False when the property is absent, matching what a missing-property read returns
     */
    public function isPropertyInitialized(object $object, string $property): bool;

    /**
     * Assign a property's value, or do nothing when the class does not declare the property.
     *
     * The silent no-op mirrors `readInitializedValue()`, and matches how the deserializer already
     * treats an absent property: decoded payloads carry keys that no generated class declares, and
     * those are skipped rather than rejected.
     *
     * Takes an instance rather than a class name because a write has nowhere to land without one.
     *
     * @param object $object   Instance to write to
     * @param string $property Property name to assign
     * @param mixed  $value    Value to assign; no type check is performed, so a value that conflicts
     *                         with the declared type throws just as a direct assignment would
     */
    public function writeValue(object $object, string $property, mixed $value): void;

    /**
     * Copy a typed `value[x]` choice back onto the plain `value` property of an extension.
     *
     * Does nothing when the object has no `value` property or no populated `value*` sibling.
     *
     * @param object $object The extension instance to normalize in place
     */
    public function copyTypedExtensionValueBack(object $object): void;
}
