<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\Utility;

/**
 * Utility for detecting FHIR versions from model instances
 *
 * @author FHIR Tools Contributors
 *
 * @description Provides functionality to detect FHIR versions from model class instances and class names
 */
class VersionDetector
{
    /**
     * Detect FHIR version from a model class instance
     *
     * @param object $model The FHIR model instance
     *
     * @return string|null The detected FHIR version (R4, R4B, R5) or null if not detected
     */
    public static function detectVersion(object $model): ?string
    {
        $className = get_class($model);

        return self::detectVersionFromClassName($className);
    }

    /**
     * Detect FHIR version from a class name
     *
     * @param string $className The fully qualified class name
     *
     * @return string|null The detected FHIR version (R4, R4B, R5) or null if not detected
     */
    public static function detectVersionFromClassName(string $className): ?string
    {
        // Extract version from namespace pattern: \Component\Models\{VERSION}\
        if (preg_match('/\\\\Component\\\\Models\\\\(R4B?|R5)\\\\/', $className, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Check if a class belongs to the Models component
     *
     * @param string $className The fully qualified class name
     *
     * @return bool True if the class belongs to the Models component, false otherwise
     */
    public static function isModelsComponentClass(string $className): bool
    {
        return str_contains($className, '\\Component\\Models\\');
    }

    /**
     * Check if a class belongs to a specific FHIR version in the Models component
     *
     * @param string $className The fully qualified class name
     * @param string $version   The FHIR version to check (R4, R4B, R5)
     *
     * @return bool True if the class belongs to the specified version, false otherwise
     */
    public static function isVersionSpecificClass(string $className, string $version): bool
    {
        return self::detectVersionFromClassName($className) === $version;
    }

    /**
     * Get the model type from a class name (Resource, DataType, Primitive, Enum)
     *
     * @param string $className The fully qualified class name
     *
     * @return string|null The model type or null if not detected
     */
    public static function getModelType(string $className): ?string
    {
        if (!self::isModelsComponentClass($className)) {
            return null;
        }

        // Extract type from namespace pattern: \Component\Models\{VERSION}\{TYPE}\
        if (preg_match('/\\\\Component\\\\Models\\\\(?:R4B?|R5)\\\\(Resource|DataType|Primitive|Enum)\\\\/', $className, $matches)) {
            return $matches[1];
        }

        return null;
    }
}
