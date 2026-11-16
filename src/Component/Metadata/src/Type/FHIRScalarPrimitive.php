<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Type;

/**
 * Reads the native value of a FHIR `boolean`, `integer` or `decimal` element.
 *
 * Generated models declare these elements as a native scalar plus their primitive wrapper, for
 * example `BooleanPrimitive|bool|null`. Deserialization builds the wrapper only when the element
 * carries an extension; otherwise the property holds the scalar. Code that wants the value either way
 * reads it through {@see unwrap()}.
 *
 * Other primitives (`StringPrimitive`, `DateTimePrimitive`, ...) are returned unchanged, because
 * their properties always hold the wrapper and callers already handle it.
 *
 * @author Ardenexal
 */
final class FHIRScalarPrimitive
{
    private const array SCALAR_PRIMITIVE_TYPES = ['boolean', 'integer', 'decimal'];

    private static ?FHIRStructureKindProviderInterface $structureKinds = null;

    private static ?FHIRModelAccessorInterface $modelAccessor = null;

    /**
     * The value inside a boolean/integer/decimal wrapper, or `$value` itself for anything else.
     *
     * An extension-only wrapper has no value and answers null.
     */
    public static function unwrap(mixed $value): mixed
    {
        if (!is_object($value) || !self::isScalarPrimitive($value)) {
            return $value;
        }

        self::$modelAccessor ??= new FHIRModelAccessor();

        return self::$modelAccessor->readInitializedValue($value, 'value');
    }

    /**
     * Whether the object is the primitive wrapper of a FHIR boolean, integer or decimal.
     */
    public static function isScalarPrimitive(object $value): bool
    {
        self::$structureKinds ??= new FHIRStructureKindProvider();

        return in_array(self::$structureKinds->nearestPrimitiveAttribute($value)?->primitiveType, self::SCALAR_PRIMITIVE_TYPES, true);
    }
}
