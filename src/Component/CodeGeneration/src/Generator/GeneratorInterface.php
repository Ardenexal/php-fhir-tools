<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Generator;

use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContextInterface;
use Ardenexal\FHIRTools\Component\CodeGeneration\Exception\GenerationException;
use Nette\PhpGenerator\ClassLike;

/**
 * Interface for FHIR code generators
 *
 * Defines the contract for generating PHP code from FHIR definitions.
 * Implementations should handle specific types of FHIR resources or structures.
 *
 * @author FHIR Tools
 *
 * @since 1.0.0
 */
interface GeneratorInterface
{
    /**
     * Generate PHP code from FHIR definition
     *
     * @param array<string, mixed>    $definition The FHIR definition to generate code from
     * @param string                  $version    The FHIR version
     * @param BuilderContextInterface $context    The builder context for managing generated types
     *
     * @return ClassLike The generated PHP class or enum
     *
     * @throws GenerationException When generation fails
     */
    public function generate(array $definition, string $version, BuilderContextInterface $context): ClassLike;

    /**
     * Check if this generator can handle the given definition
     *
     * @param array<string, mixed> $definition The FHIR definition to check
     *
     * @return bool True if this generator can handle the definition
     */
    public function canGenerate(array $definition): bool;

    /**
     * Get the priority of this generator (higher numbers = higher priority)
     *
     * @return int The priority value
     */
    public function getPriority(): int;
}
