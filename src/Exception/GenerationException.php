<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Exception;

/**
 * Exception thrown when FHIR code generation fails
 *
 * This exception is thrown when FHIR code generation encounters errors,
 * such as invalid StructureDefinitions, missing references, or unsupported
 * FHIR versions.
 *
 * @author FHIR Tools
 *
 * @since 1.0.0
 */
class GenerationException extends FHIRToolsException
{
    /**
     * Create exception for invalid StructureDefinition scenarios
     *
     * @param string $structureDefinitionUrl The URL of the invalid StructureDefinition
     * @param string $reason                 The reason why it's invalid
     *
     * @return static
     */
    public static function invalidStructureDefinition(string $structureDefinitionUrl, string $reason): static
    {
        $message = "Invalid StructureDefinition '{$structureDefinitionUrl}': {$reason}";

        return new static($message, 400, null, [
            'structure_definition_url' => $structureDefinitionUrl,
            'reason'                   => $reason,
        ]);
    }

    /**
     * Create exception for missing content reference scenarios
     *
     * @param string $contentReference The missing content reference
     * @param string $elementPath      The element path where the reference was expected
     *
     * @return static
     */
    public static function missingContentReference(string $contentReference, string $elementPath): static
    {
        $message = "Missing content reference '{$contentReference}' for element '{$elementPath}'";

        return new static($message, 404, null, [
            'content_reference' => $contentReference,
            'element_path'      => $elementPath,
        ]);
    }

    /**
     * Create exception for unsupported FHIR version scenarios
     *
     * @param string $fhirVersion The unsupported FHIR version
     *
     * @return static
     */
    public static function unsupportedFhirVersion(string $fhirVersion): static
    {
        $message = "Unsupported FHIR version '{$fhirVersion}'";

        return new static($message, 400, null, [
            'fhir_version' => $fhirVersion,
        ]);
    }

    /**
     * Create exception for invalid element path scenarios
     *
     * @param string $elementPath The invalid element path
     *
     * @return static
     */
    public static function invalidElementPath(string $elementPath): static
    {
        $message = "Invalid element path '{$elementPath}'";

        return new static($message, 400, null, [
            'element_path' => $elementPath,
        ]);
    }
}
