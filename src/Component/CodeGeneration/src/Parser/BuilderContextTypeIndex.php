<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Parser;

use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContextInterface;

/**
 * A {@see TypeIndexInterface} backed by the StructureDefinitions loaded into the builder context.
 *
 * `FHIRModelGeneratorCommand::loadPackage()` passes the full definition set — StructureDefinitions
 * included — to `BuilderContext::loadDefinitions()`, which merges into the same store
 * `getDefinition()` reads. So a type code resolves by canonical URL with no extra plumbing.
 *
 * @author Ardenexal
 */
final class BuilderContextTypeIndex implements TypeIndexInterface
{
    private const string STRUCTURE_DEFINITION_URL = 'http://hl7.org/fhir/StructureDefinition/';

    public function __construct(private readonly BuilderContextInterface $context)
    {
    }

    public function kindOf(string $typeCode): ?string
    {
        $definition = $this->context->getDefinition(self::STRUCTURE_DEFINITION_URL . $typeCode);
        $kind       = $definition['kind'] ?? null;

        return is_string($kind) ? $kind : null;
    }

    public function isAbstract(string $typeCode): bool
    {
        $definition = $this->context->getDefinition(self::STRUCTURE_DEFINITION_URL . $typeCode);

        return ($definition['abstract'] ?? false) === true;
    }
}
