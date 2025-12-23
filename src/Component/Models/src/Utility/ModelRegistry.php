<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\Utility;

use Ardenexal\FHIRTools\Component\Models\Exception\ModelsException;

/**
 * Central registry for FHIR model classes across versions
 *
 * @author FHIR Tools Contributors
 *
 * @description Provides centralized access to FHIR model classes with version-specific namespace resolution
 */
class ModelRegistry
{
    private const SUPPORTED_VERSIONS = ['R4', 'R4B', 'R5'];

    private const NAMESPACE_MAP = [
        'R4' => [
            'resource'  => 'Ardenexal\\FHIRTools\\Component\\Models\\R4\\Resource',
            'datatype'  => 'Ardenexal\\FHIRTools\\Component\\Models\\R4\\DataType',
            'primitive' => 'Ardenexal\\FHIRTools\\Component\\Models\\R4\\Primitive',
            'enum'      => 'Ardenexal\\FHIRTools\\Component\\Models\\R4\\Enum',
        ],
        'R4B' => [
            'resource'  => 'Ardenexal\\FHIRTools\\Component\\Models\\R4B\\Resource',
            'datatype'  => 'Ardenexal\\FHIRTools\\Component\\Models\\R4B\\DataType',
            'primitive' => 'Ardenexal\\FHIRTools\\Component\\Models\\R4B\\Primitive',
            'enum'      => 'Ardenexal\\FHIRTools\\Component\\Models\\R4B\\Enum',
        ],
        'R5' => [
            'resource'  => 'Ardenexal\\FHIRTools\\Component\\Models\\R5\\Resource',
            'datatype'  => 'Ardenexal\\FHIRTools\\Component\\Models\\R5\\DataType',
            'primitive' => 'Ardenexal\\FHIRTools\\Component\\Models\\R5\\Primitive',
            'enum'      => 'Ardenexal\\FHIRTools\\Component\\Models\\R5\\Enum',
        ],
    ];

    /**
     * Get the fully qualified class name for a FHIR resource
     *
     * @param string $version      The FHIR version (R4, R4B, R5)
     * @param string $resourceName The resource name (e.g., Patient, Observation)
     *
     * @return string The fully qualified class name
     *
     * @throws ModelsException If the version is not supported
     */
    public static function getResourceClass(string $version, string $resourceName): string
    {
        self::validateVersion($version);
        $namespace = self::NAMESPACE_MAP[$version]['resource'];

        return "{$namespace}\\FHIR{$resourceName}";
    }

    /**
     * Get the fully qualified class name for a FHIR backbone element
     *
     * @param string $version      The FHIR version (R4, R4B, R5)
     * @param string $resourceName The parent resource name (e.g., Patient, Observation)
     * @param string $elementName  The backbone element name (e.g., Contact, Communication)
     *
     * @return string The fully qualified class name
     *
     * @throws ModelsException If the version is not supported
     */
    public static function getBackboneElementClass(string $version, string $resourceName, string $elementName): string
    {
        self::validateVersion($version);
        $namespace = self::NAMESPACE_MAP[$version]['resource'];

        return "{$namespace}\\{$resourceName}\\FHIR{$resourceName}{$elementName}";
    }

    /**
     * Get the fully qualified class name for a FHIR data type
     *
     * @param string $version      The FHIR version (R4, R4B, R5)
     * @param string $dataTypeName The data type name (e.g., HumanName, Address)
     *
     * @return string The fully qualified class name
     *
     * @throws ModelsException If the version is not supported
     */
    public static function getDataTypeClass(string $version, string $dataTypeName): string
    {
        self::validateVersion($version);
        $namespace = self::NAMESPACE_MAP[$version]['datatype'];

        return "{$namespace}\\FHIR{$dataTypeName}";
    }

    /**
     * Get the fully qualified class name for a FHIR primitive type
     *
     * @param string $version       The FHIR version (R4, R4B, R5)
     * @param string $primitiveName The primitive type name (e.g., String, Integer)
     *
     * @return string The fully qualified class name
     *
     * @throws ModelsException If the version is not supported
     */
    public static function getPrimitiveClass(string $version, string $primitiveName): string
    {
        self::validateVersion($version);
        $namespace = self::NAMESPACE_MAP[$version]['primitive'];

        return "{$namespace}\\FHIR{$primitiveName}";
    }

    /**
     * Get the fully qualified class name for a FHIR enum
     *
     * @param string $version  The FHIR version (R4, R4B, R5)
     * @param string $enumName The enum name (e.g., AdministrativeGender, ObservationStatus)
     *
     * @return string The fully qualified class name
     *
     * @throws ModelsException If the version is not supported
     */
    public static function getEnumClass(string $version, string $enumName): string
    {
        self::validateVersion($version);
        $namespace = self::NAMESPACE_MAP[$version]['enum'];

        return "{$namespace}\\FHIR{$enumName}";
    }

    /**
     * Get the fully qualified class name for a FHIR code type (wrapper for enum)
     *
     * @param string $version  The FHIR version (R4, R4B, R5)
     * @param string $enumName The enum name (e.g., AdministrativeGender, ObservationStatus)
     *
     * @return string The fully qualified class name for the code type wrapper
     *
     * @throws ModelsException If the version is not supported
     */
    public static function getCodeTypeClass(string $version, string $enumName): string
    {
        self::validateVersion($version);
        $namespace = self::NAMESPACE_MAP[$version]['datatype'];

        return "{$namespace}\\FHIR{$enumName}Type";
    }

    /**
     * Check if a FHIR version is supported
     *
     * @param string $version The FHIR version to check
     *
     * @return bool True if the version is supported, false otherwise
     */
    public static function isSupportedVersion(string $version): bool
    {
        return in_array($version, self::SUPPORTED_VERSIONS, true);
    }

    /**
     * Get all supported FHIR versions
     *
     * @return array<string> Array of supported FHIR versions
     */
    public static function getSupportedVersions(): array
    {
        return self::SUPPORTED_VERSIONS;
    }

    /**
     * Validate that a FHIR version is supported
     *
     * @param string $version The FHIR version to validate
     *
     * @throws ModelsException If the version is not supported
     */
    private static function validateVersion(string $version): void
    {
        if (!self::isSupportedVersion($version)) {
            throw ModelsException::unsupportedVersion($version);
        }
    }
}
