<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Configuration;

/**
 * Interface for code generation configuration
 *
 * Defines the contract for managing code generation configuration
 * including output settings, naming conventions, and generation options.
 *
 * @author FHIR Tools
 *
 * @since 1.0.0
 */
interface GenerationConfigurationInterface
{
    /**
     * Get the base namespace for generated classes
     *
     * @return string The base namespace
     */
    public function getBaseNamespace(): string;

    /**
     * Get the class prefix for generated classes
     *
     * @return string The class prefix
     */
    public function getClassPrefix(): string;

    /**
     * Get the output directory for generated files
     *
     * @return string The output directory path
     */
    public function getOutputDirectory(): string;

    /**
     * Check if strict types should be enabled
     *
     * @return bool True if strict types should be enabled
     */
    public function isStrictTypesEnabled(): bool;

    /**
     * Check if PSR-12 formatting should be enforced
     *
     * @return bool True if PSR-12 formatting should be enforced
     */
    public function isPsr12FormattingEnabled(): bool;

    /**
     * Get the author name for generated files
     *
     * @return string The author name
     */
    public function getAuthorName(): string;

    /**
     * Get additional generation options
     *
     * @return array<string, mixed> The generation options
     */
    public function getOptions(): array;

    /**
     * Get a specific option value
     *
     * @param string $key     The option key
     * @param mixed  $default The default value if option is not set
     *
     * @return mixed The option value
     */
    public function getOption(string $key, mixed $default = null): mixed;

    /**
     * Check if an option is set
     *
     * @param string $key The option key
     *
     * @return bool True if the option is set
     */
    public function hasOption(string $key): bool;

    /**
     * Get the namespace for element classes
     *
     * @param string $fhirVersion The FHIR version
     *
     * @return string The element namespace
     */
    public function getElementNamespace(string $fhirVersion): string;

    /**
     * Get the namespace for enum classes
     *
     * @param string $fhirVersion The FHIR version
     *
     * @return string The enum namespace
     */
    public function getEnumNamespace(string $fhirVersion): string;
}
