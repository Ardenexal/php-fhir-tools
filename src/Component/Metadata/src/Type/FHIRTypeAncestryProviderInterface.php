<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Type;

/**
 * Answers "what does this FHIR type derive from", from the generated models rather than a table.
 *
 * FHIR's type hierarchy is encoded twice in this repository: once as PHP inheritance between generated
 * classes, and once in whatever table a consumer wrote when it needed the answer. The tables drift,
 * and they drift silently, because nothing compares them to the models.
 *
 * Ancestry is per-version and a bare type name is not: `uri` derives from `Element` in R4 and R4B and
 * from `PrimitiveType` in R5. Callers that know their version pass it. Callers that do not get the
 * R4-first order documented on {@see FHIRModelClassLocatorInterface}, which is a fallback rather than
 * a default worth relying on.
 *
 * @author Ardenexal
 */
interface FHIRTypeAncestryProviderInterface
{
    /**
     * The FHIR type names a type derives from, nearest ancestor first.
     *
     * The type itself is not included, so an answer is exactly the set a conformance test should walk.
     * A name that cannot be placed answers the empty list, and so does a type that derives from
     * nothing nameable.
     *
     * @param string      $fhirTypeName Bare FHIR type name, e.g. 'code', 'Age', 'Patient'
     * @param string|null $fhirVersion  'R4', 'R4B' or 'R5'; null searches R4, then R4B, then R5
     *
     * @return list<string>
     */
    public function ancestryOf(string $fhirTypeName, ?string $fhirVersion = null): array;
}
