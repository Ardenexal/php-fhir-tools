<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Exception;

use Exception;

/**
 * Exception thrown during FHIR code generation
 *
 * This exception is thrown when errors occur during the code generation process,
 * such as invalid FHIR definitions, missing dependencies, or generation failures.
 *
 * @author FHIR Tools
 *
 * @since 1.0.0
 */
class GenerationException extends \Exception
{
    /**
     * Additional context information about the error
     *
     * @var array<string, mixed>
     */
    private array $context;

    /**
     * Create a new GenerationException
     *
     * @param string               $message  The error message
     * @param array<string, mixed> $context  Additional context information
     * @param int                  $code     The error code
     * @param \Exception|null      $previous The previous exception
     */
    public function __construct(string $message, array $context = [], int $code = 0, ?\Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->context = $context;
    }

    /**
     * Get the additional context information
     *
     * @return array<string, mixed>
     */
    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * Create exception for invalid element path
     *
     * @param string $path The invalid path
     *
     * @return self
     */
    public static function invalidElementPath(string $path): self
    {
        return new self(
            "Invalid element path: {$path}",
            ['path' => $path],
        );
    }

    /**
     * Create exception for missing content reference
     *
     * @param string $contentReference The missing content reference
     * @param string $elementPath      The element path that references it
     *
     * @return self
     */
    public static function missingContentReference(string $contentReference, string $elementPath): self
    {
        return new self(
            "Missing content reference '{$contentReference}' for element '{$elementPath}'",
            [
                'content_reference' => $contentReference,
                'element_path'      => $elementPath,
            ],
        );
    }

    /**
     * Create exception for missing namespace
     *
     * @param string $version The FHIR version
     * @param string $type    The namespace type (element, enum)
     *
     * @return self
     */
    public static function missingNamespace(string $version, string $type): self
    {
        return new self(
            "Missing {$type} namespace for FHIR version {$version}",
            [
                'version'        => $version,
                'namespace_type' => $type,
            ],
        );
    }

    /**
     * Create exception for enum generation failure
     *
     * @param string $code   The enum code that failed
     * @param string $reason The reason for failure
     *
     * @return self
     */
    public static function enumGenerationFailed(string $code, string $reason): self
    {
        return new self(
            "Failed to generate enum for code '{$code}': {$reason}",
            [
                'code'   => $code,
                'reason' => $reason,
            ],
        );
    }

    /**
     * Create exception for unsupported definition type
     *
     * @param string $resourceType The unsupported resource type
     * @param string $url          The definition URL
     *
     * @return self
     */
    public static function unsupportedDefinitionType(string $resourceType, string $url): self
    {
        return new self(
            "Unsupported definition type '{$resourceType}' for URL '{$url}'",
            [
                'resource_type' => $resourceType,
                'url'           => $url,
            ],
        );
    }

    /**
     * Create exception for pending types remaining after generation
     *
     * @param array<string> $pendingTypes The pending type URLs
     *
     * @return self
     */
    public static function pendingTypesRemaining(array $pendingTypes): self
    {
        return new self(
            'Pending types remaining after generation: ' . implode(', ', $pendingTypes),
            ['pending_types' => $pendingTypes],
        );
    }

    /**
     * Create exception for unsupported FHIR version
     *
     * @param string $version The unsupported version
     *
     * @return self
     */
    public static function unsupportedFhirVersion(string $version): self
    {
        return new self(
            "Unsupported FHIR version: {$version}",
            ['version' => $version],
        );
    }
}
