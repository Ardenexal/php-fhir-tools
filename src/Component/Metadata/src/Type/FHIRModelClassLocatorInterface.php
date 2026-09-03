<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Type;

/**
 * Resolves a FHIR type name to the generated model class that implements it.
 *
 * The generated tree lays classes out by version and by structure kind, and several components need
 * to go from a bare type name — `Patient`, `Quantity`, `uri` — back to a class. Before this interface
 * each of them either hardcoded the namespace shape or scanned `get_declared_classes()`, and the scan
 * answered with whichever version happened to be loaded first.
 *
 * A type name alone does not identify a class, because the same name exists in every version. Callers
 * that know their version pass it and get a strictly scoped answer; callers that do not get the
 * documented R4-first order, which is a fallback and not a default worth relying on.
 *
 * @author Ardenexal
 */
interface FHIRModelClassLocatorInterface
{
    /**
     * The generated class implementing a FHIR type, or null when none matches.
     *
     * Matching is by namespace convention rather than by reading each candidate's attributes, so a
     * class whose declared FHIR type name cannot be spelled as a PHP class name — the dotted element
     * types such as `Timing.repeat` — is not reachable through this method by that dotted name.
     *
     * @param string            $fhirTypeName Bare FHIR type name, e.g. 'Patient', 'Quantity', 'uri'
     * @param string|null       $fhirVersion  'R4', 'R4B' or 'R5', matched without regard to case.
     *                                        When given, the search is scoped strictly to that
     *                                        version and a miss answers null rather than falling
     *                                        through to another release. When null, the versions are
     *                                        tried in R4, R4B, R5 order. Any other string throws:
     *                                        scoping to a version that cannot exist would answer
     *                                        null for every type, which callers read as "no such
     *                                        type" rather than "no such version".
     * @param FHIRStructureKind ...$kinds     Structure kinds worth searching, in the order given.
     *                                        Empty means every kind this locator knows how to place.
     *
     * @return class-string|null
     *
     * @throws \InvalidArgumentException when $fhirVersion is neither null nor a known release label
     */
    public function locate(string $fhirTypeName, ?string $fhirVersion = null, FHIRStructureKind ...$kinds): ?string;
}
