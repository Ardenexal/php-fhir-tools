<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Type;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRPrimitive;

/**
 * Resolves and validates FHIR types using the generated FHIR models.
 *
 * This class integrates the FHIRPath type system with the generated FHIR models
 * from src/Component/Models (or wherever they are generated). It provides
 * type checking, validation, and inference based on actual FHIR resource classes.
 *
 * @author Ardenexal
 */
class FHIRTypeResolver
{
    /**
     * Maps FHIRPath System.* type specifiers to their canonical FHIR type names.
     *
     * Per FHIRPath spec §8, System.Boolean ≡ boolean, System.Integer ≡ integer, etc.
     *
     * @var array<string, string>
     */
    private const SYSTEM_TYPE_MAP = [
        'System.Boolean'  => 'boolean',
        'System.Integer'  => 'integer',
        'System.Decimal'  => 'decimal',
        'System.String'   => 'string',
        'System.Date'     => 'date',
        'System.DateTime' => 'dateTime',
        'System.Time'     => 'time',
        'System.Quantity' => 'Quantity',
    ];

    /**
     * Primitive FHIR types mapping.
     *
     * @var array<string, string>
     */
    private const PRIMITIVE_TYPES = [
        'boolean'      => 'boolean',
        'string'       => 'string',
        'integer'      => 'integer',
        'decimal'      => 'float',
        'date'         => 'string',
        'dateTime'     => 'string',
        'time'         => 'string',
        'uri'          => 'string',
        'url'          => 'string',
        'canonical'    => 'string',
        'code'         => 'string',
        'oid'          => 'string',
        'id'           => 'string',
        'uuid'         => 'string',
        'markdown'     => 'string',
        'base64Binary' => 'string',
        'instant'      => 'string',
        'unsignedInt'  => 'integer',
        'positiveInt'  => 'integer',
    ];

    /**
     * Normalise a (potentially namespaced) type specifier to its canonical FHIR type name.
     *
     * Handles:
     *  - System.Boolean  → boolean
     *  - System.Integer  → integer
     *  - System.Decimal  → decimal
     *  - System.String   → string
     *  - System.Date     → date
     *  - System.DateTime → dateTime
     *  - System.Time     → time
     *  - System.Quantity → Quantity
     *  - FHIR.Patient    → Patient  (strips FHIR. prefix, returns bare type)
     *  - FHIR.string     → string   (FHIR primitive aliases)
     *  - Anything else   → returned as-is
     *
     * @param string $typeName Possibly namespaced type specifier
     *
     * @return string Canonical FHIR type name
     */
    public function normalizeTypeName(string $typeName): string
    {
        // System.* explicit mappings
        if (isset(self::SYSTEM_TYPE_MAP[$typeName])) {
            return self::SYSTEM_TYPE_MAP[$typeName];
        }

        // FHIR.* → strip prefix and return bare name
        if (str_starts_with($typeName, 'FHIR.')) {
            return substr($typeName, 5);
        }

        return $typeName;
    }

    /**
     * Infer the FHIR type from a PHP value.
     *
     * @param mixed $value The value to infer the type from
     *
     * @return string The inferred FHIR type name
     */
    public function inferType(mixed $value): string
    {
        if ($value === null) {
            return 'undefined';
        }

        if (is_bool($value)) {
            return 'boolean';
        }

        if (is_int($value)) {
            return 'integer';
        }

        if (is_float($value)) {
            return 'decimal';
        }

        if (is_string($value)) {
            return 'string';
        }

        if (is_object($value)) {
            // Check if the object has a FHIRPrimitive attribute
            $ref   = new \ReflectionClass($value);
            $attrs = $ref->getAttributes(FHIRPrimitive::class);

            if (!empty($attrs)) {
                /** @var FHIRPrimitive $primitive */
                $primitive = $attrs[0]->newInstance();

                return $primitive->primitiveType;
            }

            // Get the class name and extract the FHIR type
            $class = get_class($value);

            // Check if it's a generated FHIR model
            if (str_contains($class, '\\FHIR\\') || str_contains($class, '\\Models\\')) {
                // Extract the type name from the class name
                $parts = explode('\\', $class);

                return end($parts);
            }

            return 'Resource';
        }

        if (is_array($value)) {
            return 'Collection';
        }

        return 'Any';
    }

    /**
     * Check if a value is of a specific FHIR type.
     *
     * @param mixed  $value    The value to check
     * @param string $typeName The FHIR type name to check against
     *
     * @return bool True if the value is of the specified type
     */
    public function isOfType(mixed $value, string $typeName): bool
    {
        $typeName   = $this->normalizeTypeName($typeName);
        $actualType = $this->inferType($value);

        // Exact match
        if ($actualType === $typeName) {
            return true;
        }

        // Case-insensitive match
        if (strcasecmp($actualType, $typeName) === 0) {
            return true;
        }

        // Check for type compatibility
        if ($typeName === 'Any') {
            return true;
        }

        // Check if integer is compatible with decimal (case-insensitive)
        if (strcasecmp($typeName, 'decimal') === 0 && $actualType === 'integer') {
            return true;
        }

        // date, dateTime, time, and instant values are stored as plain PHP strings in this
        // implementation (no dedicated Date/DateTime/Time PHP type). A string value therefore
        // satisfies an is-date / is-dateTime / is-time / is-instant check.
        if ($actualType === 'string' && in_array($typeName, ['date', 'dateTime', 'time', 'instant'], true)) {
            return true;
        }

        // Check if value is an instance of the FHIR resource type
        if (is_object($value)) {
            $class = get_class($value);
            if (str_ends_with($class, '\\' . $typeName)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Attempt to cast a value to a specific FHIR type.
     *
     * @param mixed  $value    The value to cast
     * @param string $typeName The target FHIR type name
     *
     * @return mixed The casted value
     *
     * @throws \InvalidArgumentException If the value cannot be cast to the type
     */
    public function castToType(mixed $value, string $typeName): mixed
    {
        $typeName = $this->normalizeTypeName($typeName);

        // If already the correct type, return as-is
        if ($this->isOfType($value, $typeName)) {
            return $value;
        }

        // Extract value from FHIR primitives before casting
        $castValue = $value;
        if (is_object($value)) {
            $ref   = new \ReflectionClass($value);
            $attrs = $ref->getAttributes(FHIRPrimitive::class);

            if (!empty($attrs) && property_exists($value, 'value')) {
                $castValue = $value->value;
            }
        }

        // Normalize typeName to lowercase for matching
        $normalizedType = strtolower($typeName);

        // Primitive type casting
        return match ($normalizedType) {
            'boolean' => $this->castToBoolean($castValue),
            'string'  => $this->castToString($castValue),
            'integer' => $this->castToInteger($castValue),
            'decimal' => $this->castToDecimal($castValue),
            default   => throw new \InvalidArgumentException(sprintf('Cannot cast value to type "%s"', $typeName)),
        };
    }

    /**
     * Check if a type name is a primitive FHIR type.
     *
     * @param string $typeName
     *
     * @return bool
     */
    public function isPrimitiveType(string $typeName): bool
    {
        return isset(self::PRIMITIVE_TYPES[$typeName]);
    }

    /**
     * Get the PHP type for a FHIR primitive type.
     *
     * @param string $fhirType
     *
     * @return string|null
     */
    public function getPhpType(string $fhirType): ?string
    {
        return self::PRIMITIVE_TYPES[$fhirType] ?? null;
    }

    /**
     * Cast value to boolean.
     *
     * @param mixed $value
     *
     * @return bool
     */
    private function castToBoolean(mixed $value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        if (is_string($value)) {
            $lower = strtolower($value);
            if ($lower === 'true') {
                return true;
            }
            if ($lower === 'false') {
                return false;
            }
        }

        throw new \InvalidArgumentException('Cannot cast value to boolean');
    }

    /**
     * Cast value to string.
     *
     * @param mixed $value
     *
     * @return string
     */
    private function castToString(mixed $value): string
    {
        if (is_string($value)) {
            return $value;
        }

        if (is_scalar($value)) {
            return (string) $value;
        }

        throw new \InvalidArgumentException('Cannot cast value to string');
    }

    /**
     * Cast value to integer.
     *
     * @param mixed $value
     *
     * @return int
     */
    private function castToInteger(mixed $value): int
    {
        if (is_int($value)) {
            return $value;
        }

        if (is_float($value)) {
            return (int) $value;
        }

        if (is_string($value) && is_numeric($value)) {
            return (int) $value;
        }

        throw new \InvalidArgumentException('Cannot cast value to integer');
    }

    /**
     * Cast value to decimal.
     *
     * @param mixed $value
     *
     * @return float
     */
    private function castToDecimal(mixed $value): float
    {
        if (is_float($value)) {
            return $value;
        }

        if (is_int($value)) {
            return (float) $value;
        }

        if (is_string($value) && is_numeric($value)) {
            return (float) $value;
        }

        throw new \InvalidArgumentException('Cannot cast value to decimal');
    }
}
