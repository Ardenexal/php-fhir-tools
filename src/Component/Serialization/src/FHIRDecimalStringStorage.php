<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization;

/**
 * Side-channel storage for original FHIR decimal string representations.
 *
 * FHIR XML decimal values must be preserved exactly (e.g. "1.0", "1.00", "1.0e0",
 * "0.0000000000000000000001"). Because PHP model properties are typed as ?float,
 * the original string is lost on deserialization. This class preserves it alongside
 * the object so that normalization can emit the original string for XML output.
 *
 * Storage is keyed by (object, propertyName) using a WeakMap, so entries are
 * automatically released when the parent object is garbage-collected.
 */
final class FHIRDecimalStringStorage
{
    /** @var \WeakMap<object, array<string, string>>|null */
    private static ?\WeakMap $map = null;

    /**
     * Store the original decimal string for a property on an object.
     */
    public static function store(object $obj, string $property, string $value): void
    {
        if (self::$map === null) {
            self::$map = new \WeakMap();
        }

        $existing                 = self::$map[$obj] ?? [];
        $existing[$property]      = $value;
        self::$map[$obj]          = $existing;
    }

    /**
     * Retrieve the original decimal string for a property on an object, or null if not stored.
     */
    public static function retrieve(object $obj, string $property): ?string
    {
        if (self::$map === null) {
            return null;
        }

        return (self::$map[$obj] ?? [])[$property] ?? null;
    }
}
