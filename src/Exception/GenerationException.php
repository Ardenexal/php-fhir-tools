<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Exception;

/**
 * Exception thrown when code generation fails
 *
 * This exception is used for all code generation-related errors including:
 * - Invalid StructureDefinition data
 * - Missing content references in FHIR elements
 * - Invalid element paths in FHIR structures
 * - Enum generation failures from ValueSets
 * - Missing namespace configurations
 * - Unresolved pending types after generation
 * - Unsupported FHIR versions
 *
 * Each static factory method provides specific context information
 * to help identify the exact cause of the generation failure.
 *
 * @author FHIR Tools
 *
 * @since 1.0.0
 */
class GenerationException extends FHIRToolsException
{
    /**
     * Create exception for invalid StructureDefinition
     *
     * This method creates an exception when a StructureDefinition cannot be
     * processed due to invalid or missing data that prevents code generation.
     *
     * @param string $url    The URL of the invalid StructureDefinition
     * @param string $reason The specific reason why the StructureDefinition is invalid
     *
     * @return self The configured exception instance
     */
    public static function invalidStructureDefinition(string $url, string $reason): self
    {
        return new self(
            "Invalid StructureDefinition '{$url}': {$reason}",
            400,
            null,
            [
                'structure_definition_url' => $url,
                'reason'                   => $reason,
                'error_type'               => 'invalid_structure_definition',
            ],
        );
    }

    /**
     * Create exception for missing content reference
     *
     * This method creates an exception when a FHIR element references another
     * element via contentReference, but the referenced element cannot be found.
     *
     * @param string $contentReference The content reference that could not be resolved
     * @param string $elementPath      The path of the element that contains the reference
     *
     * @return self The configured exception instance
     */
    public static function missingContentReference(string $contentReference, string $elementPath): self
    {
        return new self(
            "Content reference '{$contentReference}' not found for element '{$elementPath}'",
            404,
            null,
            [
                'content_reference' => $contentReference,
                'element_path'      => $elementPath,
                'error_type'        => 'missing_content_reference',
            ],
        );
    }

    /**
     * Create exception for invalid element path
     *
     * This method creates an exception when a FHIR element path cannot be
     * parsed or is malformed, preventing proper code generation.
     *
     * @param string $path The invalid element path
     *
     * @return self The configured exception instance
     */
    public static function invalidElementPath(string $path): self
    {
        return new self(
            "Invalid element path: '{$path}'",
            400,
            null,
            [
                'element_path' => $path,
                'error_type'   => 'invalid_element_path',
            ],
        );
    }

    /**
     * Create exception for enum generation failure
     *
     * This method creates an exception when generating an enum from a ValueSet
     * concept fails, typically due to invalid concept data or naming conflicts.
     *
     * @param string $conceptCode The code of the concept that failed to generate
     * @param string $reason      The specific reason why enum generation failed
     *
     * @return self The configured exception instance
     */
    public static function enumGenerationFailed(string $conceptCode, string $reason): self
    {
        return new self(
            "Failed to generate enum for concept '{$conceptCode}': {$reason}",
            500,
            null,
            [
                'concept_code' => $conceptCode,
                'reason'       => $reason,
                'error_type'   => 'enum_generation_failed',
            ],
        );
    }

    /**
     * Create exception for missing namespace configuration
     *
     * This method creates an exception when a required namespace for a specific
     * FHIR version is not configured in the BuilderContext.
     *
     * @param string $version       The FHIR version that is missing a namespace
     * @param string $namespaceType The type of namespace (element, enum, etc.)
     *
     * @return self The configured exception instance
     */
    public static function missingNamespace(string $version, string $namespaceType): self
    {
        return new self(
            "No {$namespaceType} namespace found for FHIR version '{$version}'",
            404,
            null,
            [
                'fhir_version'   => $version,
                'namespace_type' => $namespaceType,
                'error_type'     => 'missing_namespace',
            ],
        );
    }

    /**
     * Create exception for unresolved pending types
     *
     * This method creates an exception when code generation completes but
     * there are still pending types that could not be resolved, indicating
     * incomplete generation or missing dependencies.
     *
     * @param array<string, string> $pendingTypes Array of pending type URLs and class names
     *
     * @return self The configured exception instance
     */
    public static function pendingTypesRemaining(array $pendingTypes): self
    {
        return new self(
            'Code generation incomplete: ' . count($pendingTypes) . ' pending types remain unresolved',
            500,
            null,
            [
                'pending_types' => $pendingTypes,
                'pending_count' => count($pendingTypes),
                'error_type'    => 'pending_types_remaining',
            ],
        );
    }

    /**
     * Create exception for unsupported FHIR version
     *
     * This method creates an exception when an unsupported FHIR version
     * is requested for code generation.
     *
     * @param string $version The unsupported FHIR version
     *
     * @return self The configured exception instance
     */
    public static function unsupportedFhirVersion(string $version): self
    {
        return new self(
            "Unsupported FHIR version: '{$version}'",
            400,
            null,
            [
                'fhir_version' => $version,
                'error_type'   => 'unsupported_fhir_version',
            ],
        );
    }
}
