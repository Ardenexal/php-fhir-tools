<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Parser;

/**
 * Answers what kind of thing a FHIR type code names — resource, complex type, or primitive.
 *
 * A narrow seam over the loaded StructureDefinitions, extracted so that classification logic can be
 * unit-tested against a committed fixture instead of requiring a fully built `BuilderContext`, and
 * so the answer comes from specification data rather than a naming heuristic.
 *
 * The heuristic this replaces — "capitalised means resource" — is wrong in a way that matters:
 * `Meta` is capitalised and is a `complex-type`, and three R4 operations declare `return:Meta`.
 *
 * @author Ardenexal
 */
interface TypeIndexInterface
{
    /**
     * The `StructureDefinition.kind` for a type code.
     *
     * @param string $typeCode A FHIR type code such as `Bundle`, `Meta` or `code`
     *
     * @return string|null `resource`, `complex-type`, `primitive-type`, or null when unknown
     */
    public function kindOf(string $typeCode): ?string;
}
