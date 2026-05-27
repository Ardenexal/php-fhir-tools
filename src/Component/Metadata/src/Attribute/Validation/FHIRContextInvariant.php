<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation;

/**
 * Declares a FHIRPath co-occurrence constraint for a named FHIR extension.
 *
 * Maps to `StructureDefinition.contextInvariant[]` — each entry is a FHIRPath
 * expression that must evaluate to `true` on the **containing element** whenever
 * the extension is present. Multiple attributes use AND semantics: all expressions
 * must pass.
 *
 * Example: `["line.exists()"]` on an address extension means whenever the extension
 * is attached to an `Address`, `Address.line` must be populated.
 *
 * @see https://www.hl7.org/fhir/structuredefinition-definitions.html#StructureDefinition.contextInvariant
 *
 * @author Ardenexal
 */
#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE)]
final class FHIRContextInvariant
{
    /**
     * @param string $expression FHIRPath expression evaluated on the containing element
     */
    public function __construct(
        public readonly string $expression,
    ) {
    }
}
