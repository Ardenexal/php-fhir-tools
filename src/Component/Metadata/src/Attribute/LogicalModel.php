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
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
final class LogicalModel
{
    public function __construct(
        public readonly string $url,
        public readonly string $name,
        public readonly string $fhirVersion,
        public readonly ?string $xmlNamespace = null,
    ) {
    }
}
