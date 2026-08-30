<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Attribute;

/**
 * Marks a generated class as a logical model StructureDefinition.
 *
 * Applied to classes generated from StructureDefinitions with kind=logical,
 * derivation=specialization — including CDA R2 and AU CDA extension classes.
 * The xmlNamespace field is non-null only for XML-only serialisation targets
 * (e.g. CDA uses urn:hl7-org:v3). A null value means JSON serialisation
 * is permitted.
 *
 * `name` is the StructureDefinition name — a *type* identifier, and not always an XML element name.
 * The two diverge when a definition refines another published type instead of introducing one:
 * `au-ClinicalDocument` is a profile identifier, and CDA requires `<ClinicalDocument>` on the wire.
 * `refines` records that relationship — the canonical URL of the type this one refines, taken from
 * the StructureDefinition's own `type` field — so consumers can resolve the wire name by following
 * it rather than guessing from the shape of `url` and `name`. It is null for a definition that
 * introduces a type of its own, which is the common case and where `name` is already the element
 * name.
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
final class LogicalModel
{
    public function __construct(
        public readonly string $url,
        public readonly string $name,
        public readonly string $fhirVersion,
        public readonly ?string $xmlNamespace = null,
        public readonly ?string $refines = null,
    ) {
    }
}
