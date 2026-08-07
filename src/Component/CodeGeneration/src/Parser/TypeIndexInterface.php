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

    /**
     * Whether a type is declared abstract, and therefore has no concrete generated class.
     *
     * Not a detail: R5 declares **four** abstract resource types (`Resource`, `DomainResource`,
     * `CanonicalResource`, `MetadataResource`) and R4 declares two. A hand-maintained list of them
     * goes stale on the next FHIR version, and the failure is an unresolvable type in generated
     * output rather than anything the type system catches.
     */
    public function isAbstract(string $typeCode): bool;
}
