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
    /**
     * Create exception for an OperationDefinition that cannot yield legal PHP identifiers.
     *
     * Deliberately fatal rather than skip-and-continue: a generator that silently drops one of two
     * colliding names produces output that looks complete. See
     * `.goat-flow/learning-loop/footguns/valueset-enum-case-naming.md`.
     *
     * @param string $reason What could not be derived, and from what
     */
    public static function operationNamingFailed(string $reason): self
    {
        return new self(
            "Operation code generation failed: {$reason}",
            ['reason' => $reason],
        );
    }

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
     * Create exception for a `baseDefinition` that resolves to no loadable class.
     *
     * Raised instead of emitting the derived FQCN as a best guess. A generated `extends` clause
     * naming a class that does not exist is not a recoverable degradation: PHPStan treats it as a
     * severe error and aborts analysis of the whole consuming project, so one unresolvable
     * definition hides every other finding in the generated tree. Failing here keeps the error
     * attached to the definition that caused it.
     *
     * @param string $baseDefinitionUrl The unresolvable `baseDefinition` canonical URL
     * @param string $derivedFqcn       The FQCN derived from it, which does not exist
     *
     * @return self
     */
    public static function unresolvableBaseDefinition(string $baseDefinitionUrl, string $derivedFqcn): self
    {
        return new self(
            "Could not resolve baseDefinition URL '{$baseDefinitionUrl}': derived class "
            . "'{$derivedFqcn}' does not exist. Ensure the package providing this type is included "
            . 'in your --package list.',
            [
                'base_definition_url' => $baseDefinitionUrl,
                'derived_fqcn'        => $derivedFqcn,
            ],
        );
    }

    /**
     * Create exception for an admitted CDA datatype whose published type name cannot be sourced.
     *
     * Raised instead of falling back to the URL's last segment or the generated class name. Those
     * two guesses are wrong for nine of the thirty datatypes a CDA element can admit (`.../IVL-PQ`
     * is published as `IVL_PQ`), and an element carrying a wrong `xsi:type` is well-formed,
     * plausible, and rejected only by a schema-validating receiver — the exact silent failure this
     * discriminator exists to prevent. Failing here keeps the error attached to the element that
     * caused it.
     *
     * @param string $elementPath The element admitting the datatype
     * @param string $typeUrl     The admitted datatype's canonical URL, which resolves to no name
     *
     * @return self
     */
    public static function unresolvablePolymorphicTypeName(string $elementPath, string $typeUrl): self
    {
        return new self(
            "Element '{$elementPath}' admits datatype '{$typeUrl}', which resolves to no published "
            . 'type name. A polymorphic CDA element must name each datatype it admits, and the name '
            . 'is read from the definition rather than derived. Ensure the package providing this '
            . 'type is included in your --package list.',
            [
                'element_path' => $elementPath,
                'type_url'     => $typeUrl,
            ],
        );
    }

    /**
     * Create exception for a polymorphic element whose admitted datatypes share no ancestor.
     *
     * A polymorphic element is typed to the nearest datatype all of its admitted types derive from.
     * With no shared ancestor there is no such type, and the alternative — keeping the first admitted
     * datatype and dropping the rest — is what made these elements unable to hold their own legal
     * values in the first place. Unreachable for the currently pinned packages, where all nine
     * polymorphic elements resolve; kept because a future pin could add a type outside the hierarchy.
     *
     * @param string       $elementPath The polymorphic element
     * @param list<string> $typeUrls    Canonical URLs of every datatype it admits
     *
     * @return self
     */
    public static function polymorphicElementWithoutCommonType(string $elementPath, array $typeUrls): self
    {
        return new self(
            "Element '{$elementPath}' admits datatypes that share no common ancestor: "
            . implode(', ', $typeUrls)
            . '. A polymorphic CDA element must be typed to the nearest datatype all of its admitted '
            . 'types derive from, and no such type exists here.',
            [
                'element_path' => $elementPath,
                'type_urls'    => $typeUrls,
            ],
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

    /**
     * Create exception for multiple collected validation errors
     *
     * @param array<mixed> $errors Array of validation errors collected during generation
     *
     * @return self
     */
    public static function multipleValidationErrors(array $errors): self
    {
        $errorCount = count($errors);

        return new self(
            "Validation failed with {$errorCount} error(s)",
            [
                'errors'      => $errors,
                'error_count' => $errorCount,
                'error_type'  => 'multiple_validation_errors',
            ],
        );
    }
}
