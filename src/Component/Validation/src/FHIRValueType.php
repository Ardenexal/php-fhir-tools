<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRPrimitive;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;

/**
 * Reads the FHIR type code of a value as the generated models hold it.
 *
 * An object's type comes from its class: the class's own #[FHIRPrimitive], #[FHIRComplexType] or
 * #[FhirResource] attribute, or the base type of a #[FHIRProfile] class. The class's own attribute is
 * read before its parents', so an `Age` (which extends `Quantity`) is an Age, not a Quantity.
 *
 * Boolean, integer and decimal values are held as bare PHP scalars: `bool`, `int`, and a numeric
 * `string` for a decimal. Any other string could be one of several string-like types, so its type
 * cannot be told and is null, as is any value whose class declares none.
 */
final class FHIRValueType
{
    /** @var array<class-string, string|null> FHIR type per PHP class, once read */
    private static array $typeByClass = [];

    public static function of(mixed $value): ?string
    {
        return match (true) {
            is_bool($value)   => 'boolean',
            is_int($value)    => 'integer',
            is_float($value)  => 'decimal',
            is_string($value) => is_numeric($value) ? 'decimal' : null,
            is_object($value) => self::$typeByClass[$value::class] ??= self::classType(new \ReflectionClass($value)),
            default           => null,
        };
    }

    /**
     * The FHIR type a generated class models, from the nearest class in its hierarchy that declares one.
     *
     * @param \ReflectionClass<object> $class
     */
    private static function classType(\ReflectionClass $class): ?string
    {
        for ($current = $class; $current !== false; $current = $current->getParentClass()) {
            $type = ($current->getAttributes(FHIRPrimitive::class)[0] ?? null)?->newInstance()->primitiveType
                ?? ($current->getAttributes(FHIRComplexType::class)[0] ?? null)?->newInstance()->typeName
                ?? ($current->getAttributes(FhirResource::class)[0] ?? null)?->newInstance()->type
                ?? ($current->getAttributes(FHIRProfile::class)[0] ?? null)?->newInstance()->baseType;

            if ($type !== null && $type !== '') {
                return $type;
            }
        }

        return null;
    }
}
