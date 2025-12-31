<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Type;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRPrimitive;
use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;

/**
 * Resolves and validates FHIR types using the generated FHIR models.
 *
 * This class integrates the FHIRPath type system with the generated FHIR models
 * from src/Component/Models (or wherever they are generated). It provides
 * type checking, validation, and inference based on actual FHIR resource classes.
 *
 * Uses PHP attributes (FHIRPrimitive, FhirResource, FHIRComplexType) for FHIR version-safe
 * type inference instead of relying on namespace patterns.
 *
 * @author Ardenexal
 */
class FHIRTypeResolver
{
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
     * Infer the FHIR type from a PHP value.
     *
     * Uses PHP attributes (FHIRPrimitive, FhirResource, FHIRComplexType) for version-safe type inference.
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
            // Try to get type from PHP attributes first (FHIR version-safe approach)
            $reflectionClass = new \ReflectionClass($value);

            // Check for FHIRPrimitive attribute
            $primitiveAttributes = $reflectionClass->getAttributes(FHIRPrimitive::class);
            if (!empty($primitiveAttributes)) {
                $primitiveAttr = $primitiveAttributes[0]->newInstance();

                return $primitiveAttr->primitiveType;
            }

            // Check for FhirResource attribute
            $resourceAttributes = $reflectionClass->getAttributes(FhirResource::class);
            if (!empty($resourceAttributes)) {
                $resourceAttr = $resourceAttributes[0]->newInstance();

                return $resourceAttr->type;
            }

            // Check for FHIRComplexType attribute
            $complexTypeAttributes = $reflectionClass->getAttributes(FHIRComplexType::class);
            if (!empty($complexTypeAttributes)) {
                $complexTypeAttr = $complexTypeAttributes[0]->newInstance();

                return $complexTypeAttr->typeName;
            }

            // Fallback: Use class name-based inference (for backward compatibility)
            $class = get_class($value);

            // Check for FHIR primitive types from Models component or test fixtures
            if (str_contains($class, '\\Primitive\\FHIR')) {
                // Extract primitive type name: FHIRBoolean -> Boolean, FHIRString -> String
                $className = basename(str_replace('\\', '/', $class));
                if (str_starts_with($className, 'FHIR')) {
                    $typeName = substr($className, 4); // Remove 'FHIR' prefix

                    // Normalize to lowercase for common types
                    return match (strtolower($typeName)) {
                        'boolean'  => 'boolean',
                        'string'   => 'string',
                        'integer'  => 'integer',
                        'decimal'  => 'decimal',
                        'date'     => 'date',
                        'datetime' => 'dateTime',
                        'time'     => 'time',
                        default    => $typeName,
                    };
                }
            }

            // Check for FHIR resource types from Models component or test fixtures
            if (str_contains($class, '\\Resource\\FHIR')) {
                $className = basename(str_replace('\\', '/', $class));
                if (str_starts_with($className, 'FHIR')) {
                    return substr($className, 4); // Remove 'FHIR' prefix: FHIRPatient -> Patient
                }
            }

            // Check for DataType from Models component or test fixtures
            if (str_contains($class, '\\DataType\\FHIR')) {
                $className = basename(str_replace('\\', '/', $class));
                if (str_starts_with($className, 'FHIR')) {
                    return substr($className, 4); // Remove 'FHIR' prefix
                }
            }

            // Fallback: Check if it's any FHIR model class
            if (str_contains($class, '\\FHIR\\') || str_contains($class, '\\Models\\')) {
                $parts     = explode('\\', $class);
                $className = end($parts);
                // Remove FHIR prefix if present
                if (str_starts_with($className, 'FHIR')) {
                    return substr($className, 4);
                }

                return $className;
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
     * Uses PHP attributes when available for version-safe type checking.
     *
     * @param mixed  $value    The value to check
     * @param string $typeName The FHIR type name to check against
     *
     * @return bool True if the value is of the specified type
     */
    public function isOfType(mixed $value, string $typeName): bool
    {
        $actualType = $this->inferType($value);

        // Exact match (case-insensitive for primitives)
        if (strcasecmp($actualType, $typeName) === 0) {
            return true;
        }

        // Check for type compatibility
        if ($typeName === 'Any') {
            return true;
        }

        // Check if integer is compatible with decimal
        if (strcasecmp($typeName, 'decimal') === 0 && strcasecmp($actualType, 'integer') === 0) {
            return true;
        }

        // Check if value is an instance of a FHIR model class
        if (is_object($value)) {
            // Try to get type from PHP attributes first (FHIR version-safe approach)
            $reflectionClass = new \ReflectionClass($value);

            // Check FHIRPrimitive attribute
            $primitiveAttributes = $reflectionClass->getAttributes(FHIRPrimitive::class);
            if (!empty($primitiveAttributes)) {
                $primitiveAttr = $primitiveAttributes[0]->newInstance();

                return strcasecmp($primitiveAttr->primitiveType, $typeName) === 0;
            }

            // Check FhirResource attribute
            $resourceAttributes = $reflectionClass->getAttributes(FhirResource::class);
            if (!empty($resourceAttributes)) {
                $resourceAttr = $resourceAttributes[0]->newInstance();

                return strcasecmp($resourceAttr->type, $typeName) === 0;
            }

            // Check FHIRComplexType attribute
            $complexTypeAttributes = $reflectionClass->getAttributes(FHIRComplexType::class);
            if (!empty($complexTypeAttributes)) {
                $complexTypeAttr = $complexTypeAttributes[0]->newInstance();

                return strcasecmp($complexTypeAttr->typeName, $typeName) === 0;
            }

            // Fallback: class name-based checks for backward compatibility
            $class = get_class($value);

            // Check for exact class name match with FHIR prefix
            if (str_ends_with($class, '\\FHIR' . $typeName)) {
                return true;
            }

            // Check without FHIR prefix
            if (str_ends_with($class, '\\' . $typeName)) {
                return true;
            }

            // Check for primitive value property matching the type
            if (property_exists($value, 'value')) {
                // For FHIR primitives, also check the internal value type
                $primitiveType = $this->inferType($value->value);
                if (strcasecmp($primitiveType, $typeName) === 0) {
                    return true;
                }
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
     * @return mixed The casted value (may return the PHP primitive or the FHIR value property)
     *
     * @throws \InvalidArgumentException If the value cannot be cast to the type
     */
    public function castToType(mixed $value, string $typeName): mixed
    {
        // If already the correct type, return as-is
        if ($this->isOfType($value, $typeName)) {
            return $value;
        }

        // If value is a FHIR primitive object, extract the value property
        if (is_object($value) && property_exists($value, 'value')) {
            $value = $value->value;
        }

        // Primitive type casting (case-insensitive)
        $lowerTypeName = strtolower($typeName);

        return match ($lowerTypeName) {
            'boolean' => $this->castToBoolean($value),
            'string'  => $this->castToString($value),
            'integer' => $this->castToInteger($value),
            'decimal' => $this->castToDecimal($value),
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
