<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Service;

/**
 * Interface for code generation results
 *
 * Represents the result of a code generation operation,
 * including generated files, statistics, and any errors or warnings.
 *
 * @author FHIR Tools
 *
 * @since 1.0.0
 */
interface GenerationResultInterface
{
    /**
     * Check if the generation was successful
     *
     * @return bool True if generation was successful
     */
    public function isSuccessful(): bool;

    /**
     * Get the list of generated files
     *
     * @return array<string> List of generated file paths
     */
    public function getGeneratedFiles(): array;

    /**
     * Get generation statistics
     *
     * @return array<string, int> Statistics about the generation process
     */
    public function getStatistics(): array;

    /**
     * Get any errors that occurred during generation
     *
     * @return array<string> List of error messages
     */
    public function getErrors(): array;

    /**
     * Get any warnings that occurred during generation
     *
     * @return array<string> List of warning messages
     */
    public function getWarnings(): array;

    /**
     * Get the total number of classes generated
     *
     * @return int The number of classes generated
     */
    public function getClassCount(): int;

    /**
     * Get the total number of enums generated
     *
     * @return int The number of enums generated
     */
    public function getEnumCount(): int;

    /**
     * Get the generation duration in seconds
     *
     * @return float The generation duration
     */
    public function getDuration(): float;

    /**
     * Add a generated file to the result
     *
     * @param string $filePath The path of the generated file
     */
    public function addGeneratedFile(string $filePath): void;

    /**
     * Add an error to the result
     *
     * @param string $error The error message
     */
    public function addError(string $error): void;

    /**
     * Add a warning to the result
     *
     * @param string $warning The warning message
     */
    public function addWarning(string $warning): void;

    /**
     * Set generation statistics
     *
     * @param array<string, int> $statistics The statistics
     */
    public function setStatistics(array $statistics): void;

    /**
     * Set the generation duration
     *
     * @param float $duration The duration in seconds
     */
    public function setDuration(float $duration): void;
}
