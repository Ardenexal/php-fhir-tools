<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Service;

use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContextInterface;
use Ardenexal\FHIRTools\Component\CodeGeneration\Exception\GenerationException;
use Ardenexal\FHIRTools\Component\CodeGeneration\Package\PackageInterface;

/**
 * Interface for FHIR code generation service
 *
 * Defines the contract for the main code generation service that orchestrates
 * the generation of PHP code from FHIR packages and definitions.
 *
 * @author FHIR Tools
 *
 * @since 1.0.0
 */
interface CodeGenerationServiceInterface
{
    /**
     * Generate PHP code from a FHIR package
     *
     * @param PackageInterface     $package         The FHIR package to generate code from
     * @param string               $outputDirectory The directory to write generated files to
     * @param array<string, mixed> $options         Generation options
     *
     * @return GenerationResultInterface The generation result
     *
     * @throws GenerationException When generation fails
     */
    public function generateFromPackage(
        PackageInterface $package,
        string $outputDirectory,
        array $options = []
    ): GenerationResultInterface;

    /**
     * Generate PHP code from specific FHIR definitions
     *
     * @param array<string, array<string, mixed>> $definitions     The FHIR definitions to generate code from
     * @param string                              $fhirVersion     The FHIR version
     * @param string                              $outputDirectory The directory to write generated files to
     * @param array<string, mixed>                $options         Generation options
     *
     * @return GenerationResultInterface The generation result
     *
     * @throws GenerationException When generation fails
     */
    public function generateFromDefinitions(
        array $definitions,
        string $fhirVersion,
        string $outputDirectory,
        array $options = []
    ): GenerationResultInterface;

    /**
     * Get the builder context used for generation
     *
     * @return BuilderContextInterface The builder context
     */
    public function getBuilderContext(): BuilderContextInterface;

    /**
     * Set generation options
     *
     * @param array<string, mixed> $options The generation options
     */
    public function setOptions(array $options): void;

    /**
     * Get current generation options
     *
     * @return array<string, mixed> The current options
     */
    public function getOptions(): array;
}
