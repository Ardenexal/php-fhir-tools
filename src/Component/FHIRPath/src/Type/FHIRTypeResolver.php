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
     * FHIR type hierarchy: maps each derived type to its immediate parent.
     *
     * Used by isOfType() to test conformance ("is a subtype of"), e.g.
     * code → string means 'code is string' returns true.
     *
     * @var array<string, string>
     */
    private const TYPE_PARENTS = [
        'code'         => 'string',
        'id'           => 'string',
        'markdown'     => 'string',
        'base64Binary' => 'string',
        'uri'          => 'string',
        'url'          => 'uri',
        'canonical'    => 'uri',
        'oid'          => 'uri',
        'uuid'         => 'uri',
        'positiveInt'  => 'integer',
        'unsignedInt'  => 'integer',
        'instant'      => 'dateTime',
        // FHIR profile types that extend Quantity (per FHIR spec §4.2 data type hierarchy)
        'Age'          => 'Quantity',
        'Count'        => 'Quantity',
        'Distance'     => 'Quantity',
        'Duration'     => 'Quantity',
        'Money'        => 'Quantity',
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

        // FHIRTypedScalar wraps a PHP scalar with its FHIR type name to preserve
        // FHIR type context for resource properties stored as PHP scalars.
        if ($value instanceof FHIRTypedScalar) {
            return $value->fhirType;
        }

        // FHIRTypedCollection wraps a raw data array with its FHIR type name to preserve
        // FHIR type context for complex types deserialized as PHP arrays (e.g. HumanName, Age).
        if ($value instanceof FHIRTypedCollection) {
            return $value->fhirType;
        }

        if (is_bool($value)) {
            return 'boolean';
        }

        if (is_int($value)) {
            return 'integer';
        }

        if ($value instanceof FHIRPathDecimal) {
            return 'decimal';
        }

        if (is_float($value)) {
            return 'decimal';
        }

        // FHIRPath literal wrappers carry explicit type information
        if ($value instanceof FHIRPathTemporalTypeInterface) {
            return $value->getTemporalTypeName();
        }

        if (is_string($value)) {
            // Detect temporal string patterns from plain PHP strings in resource arrays.
            // FHIRPath literal dates/times use FHIRPathTemporalTypeInterface (handled above),
            // but resource property values arrive as plain strings and need the same treatment.
            if (str_starts_with($value, 'T') && preg_match('/^T\d{2}/', $value)) {
                return 'time';
            }

            if (preg_match('/^\d{4}-\d{2}-\d{2}T/', $value)) {
                return 'dateTime';
            }

            if (preg_match('/^\d{4}(-\d{2}(-\d{2})?)?$/', $value)) {
                return 'date';
            }

            return 'string';
        }

        if (is_object($value)) {
            // Walk the class hierarchy to find a FHIRPrimitive attribute — subclasses
            // (e.g. NameUseType → CodePrimitive → StringPrimitive) carry it on an ancestor.
            $ref       = new \ReflectionClass($value);
            $primitive = $this->findPrimitiveAttribute($ref);

            if ($primitive !== null) {
                return $primitive->primitiveType;
            }

            // FHIR resources carry a canonical type name in their #[FhirResource(type: '...')]
            // attribute (e.g. PatientResource → 'Patient'). Prefer this over the PHP class
            // short name to avoid 'PatientResource' ≠ 'Patient' mismatches.
            $resourceType = $this->findResourceTypeFromAttribute($ref);
            if ($resourceType !== null) {
                return $resourceType;
            }

            // FHIR complex data types (HumanName, Quantity, Age, etc.) carry a canonical type
            // name in their #[FHIRComplexType(typeName: '...')] attribute. Walk the class
            // hierarchy so that subclasses (Age extends Quantity) are also matched.
            $complexType = $this->findComplexTypeFromAttribute($ref);
            if ($complexType !== null) {
                return $complexType;
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
     * @param bool   $strict   When false (default), walks the FHIR type hierarchy so that
     *                         subtypes conform to their parents (e.g. 'code is string' = true).
     *                         When true, only exact type identity is tested — used by ofType()
     *                         and as() which must NOT include subtypes per the FHIRPath spec.
     *
     * @return bool True if the value is of the specified type
     */
    public function isOfType(mixed $value, string $typeName, bool $strict = false): bool
    {
        // Detect FHIRPath System.* type specifiers BEFORE normalization so that
        // 'Boolean' (= System.Boolean) can be distinguished from 'boolean' (= FHIR.boolean).
        $fhirPathSystemPrimitives = ['Boolean', 'Integer', 'Decimal', 'String', 'Date', 'DateTime', 'Time'];
        $isRequestingSystemType   = str_starts_with($typeName, 'System.')
            || in_array($typeName, $fhirPathSystemPrimitives, true);

        $typeName   = $this->normalizeTypeName($typeName);
        $actualType = $this->inferType($value);

        // FHIR typed scalars (PHP bools/ints/floats/strings from FHIR resource properties)
        // and FHIR model objects (primitive wrappers, resources, complex types) are FHIR
        // namespace types, not System types.  Block System.* matching for both.
        if ($isRequestingSystemType) {
            if ($value instanceof FHIRTypedScalar) {
                return false;
            }

            if (is_object($value) && $this->isFhirModelObject($value)) {
                return false;
            }
        }

        // Exact match
        if ($actualType === $typeName) {
            return true;
        }

        // Case-insensitive match — kept for PHP scalar vs capitalized System type:
        // e.g. PHP bool `true` should match `Boolean` (System.Boolean)
        if (strcasecmp($actualType, $typeName) === 0) {
            return true;
        }

        // Check for type compatibility
        if ($typeName === 'Any') {
            return true;
        }

        // Walk the FHIR type hierarchy: e.g. 'code' conforms to 'string'.
        // Only for the `is` operator — ofType() and as() use strict matching.
        if (!$strict) {
            $checkType = $actualType;
            while (isset(self::TYPE_PARENTS[$checkType])) {
                $checkType = self::TYPE_PARENTS[$checkType];
                if ($checkType === $typeName || strcasecmp($checkType, $typeName) === 0) {
                    return true;
                }
            }
        }

        // Check if value is an instance of the FHIR resource type (PHP class name ends with type)
        if (is_object($value)) {
            $class = get_class($value);
            if (str_ends_with($class, '\\' . $typeName)) {
                return true;
            }
        }

        // For the `is` operator (non-strict), walk the PHP class inheritance chain.
        // FHIR profiles like Age (extends Quantity), Duration (extends Quantity) are
        // represented in the generated models as PHP subclasses. Walking the hierarchy
        // allows `Age is Quantity` to return true without needing explicit TYPE_PARENTS
        // entries for every profile.
        if (!$strict && is_object($value)) {
            $parentRef = (new \ReflectionClass($value))->getParentClass();
            while ($parentRef !== false) {
                $parentTypeName = $this->findComplexTypeFromAttribute($parentRef)
                    ?? $this->findResourceTypeFromAttribute($parentRef);
                if ($parentTypeName !== null && (
                    $parentTypeName === $typeName || strcasecmp($parentTypeName, $typeName) === 0
                )) {
                    return true;
                }

                // Also check the bare PHP class short name (for models without attributes)
                $parts     = explode('\\', $parentRef->getName());
                $shortName = (string) end($parts);
                if ($shortName === $typeName || strcasecmp($shortName, $typeName) === 0) {
                    return true;
                }

                $parentRef = $parentRef->getParentClass();
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
     * Check if a raw type specifier (before normalization) is a recognized FHIRPath/FHIR type.
     *
     * Accepts:
     *  - System.* qualified names (System.Boolean, System.Integer, …)
     *  - FHIR primitive types (boolean, string, integer, decimal, …)
     *  - The special 'Any' wildcard
     *  - FHIR complex/resource type names (capitalized identifiers, e.g. Patient, HumanName)
     *  - FHIR.* qualified names (FHIR.Patient, …)
     *
     * Rejects names that don't match any of these patterns (e.g. 'string1').
     *
     * @param string $rawTypeName The type name as it appears in the expression (before normalization)
     *
     * @return bool
     */
    public function isKnownTypeName(string $rawTypeName): bool
    {
        // Fully-qualified System.* names
        if (isset(self::SYSTEM_TYPE_MAP[$rawTypeName])) {
            return true;
        }

        // Normalize and check the resolved name
        $normalized = $this->normalizeTypeName($rawTypeName);

        // Known FHIR primitive types
        if (isset(self::PRIMITIVE_TYPES[$normalized])) {
            return true;
        }

        // Special wildcard accepted by isOfType
        if ($normalized === 'Any') {
            return true;
        }

        // FHIR complex/resource types are CamelCase (start with uppercase, letters/digits only)
        if (preg_match('/^[A-Z][a-zA-Z0-9]*$/', $normalized)) {
            return true;
        }

        return false;
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
     * Walk the class hierarchy of $ref looking for a #[FHIRComplexType(typeName: '...')] attribute.
     *
     * Returns the canonical FHIR type string (e.g. 'HumanName', 'Quantity', 'Age') from the
     * FIRST class in the hierarchy that carries the attribute — i.e., the most-derived type.
     *
     * @param \ReflectionClass<object> $ref
     */
    private function findComplexTypeFromAttribute(\ReflectionClass $ref): ?string
    {
        // Only check the given class (not parents) — we want the most-specific type.
        // Callers walk parents themselves when they need hierarchy traversal.
        foreach ($ref->getAttributes() as $attr) {
            if (str_ends_with($attr->getName(), 'FHIRComplexType')) {
                $args     = $attr->getArguments();
                $typeName = $args['typeName'] ?? $args[0] ?? null;

                return is_string($typeName) ? $typeName : null;
            }
        }

        return null;
    }

    /**
     * Walk the class hierarchy of $ref looking for a #[FhirResource(type: '...')] attribute.
     *
     * Returns the canonical FHIR resource type string (e.g. 'Patient') when found,
     * or null when the class tree carries no such attribute.
     *
     * @param \ReflectionClass<object> $ref
     */
    private function findResourceTypeFromAttribute(\ReflectionClass $ref): ?string
    {
        do {
            foreach ($ref->getAttributes() as $attr) {
                if (str_ends_with($attr->getName(), 'FhirResource')) {
                    $args = $attr->getArguments();
                    $type = $args['type'] ?? $args[0] ?? null;

                    return is_string($type) ? $type : null;
                }
            }

            $ref = $ref->getParentClass();
        } while ($ref !== false);

        return null;
    }

    /**
     * Returns true if the object is a generated FHIR model class.
     *
     * Detection: walks the class hierarchy looking for any PHP attribute whose short
     * class name begins with 'Fhir' or 'FHIR'. All generated model classes carry at
     * least one such attribute (FHIRPrimitive, FhirResource, FhirComplexType, etc.).
     * This approach is namespace-independent and survives refactors.
     */
    private function isFhirModelObject(mixed $value): bool
    {
        if (!is_object($value)) {
            return false;
        }

        $ref = new \ReflectionClass($value);
        do {
            foreach ($ref->getAttributes() as $attr) {
                $name      = $attr->getName();
                $shortName = substr($name, strrpos($name, '\\') + 1);
                if (str_starts_with($shortName, 'Fhir') || str_starts_with($shortName, 'FHIR')) {
                    return true;
                }
            }

            $ref = $ref->getParentClass();
        } while ($ref !== false);

        return false;
    }

    /**
     * Walk the class hierarchy of $ref looking for a #[FHIRPrimitive] attribute.
     *
     * Returns the first FHIRPrimitive instance found, or null when the class tree
     * carries no such attribute.
     *
     * @param \ReflectionClass<object> $ref
     */
    private function findPrimitiveAttribute(\ReflectionClass $ref): ?FHIRPrimitive
    {
        do {
            $attrs = $ref->getAttributes(FHIRPrimitive::class);
            if (!empty($attrs)) {
                /** @var FHIRPrimitive */
                return $attrs[0]->newInstance();
            }

            $ref = $ref->getParentClass();
        } while ($ref !== false);

        return null;
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
