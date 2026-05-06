<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Attribute;

/**
 * Attribute for typed FHIR extension classes generated from named extension StructureDefinitions.
 *
 * Applied by the IG generator to classes that represent a specific named FHIR extension
 * (i.e., a StructureDefinition with type=Extension and derivation=constraint). Each such
 * class extends the base Extension type but constrains the URL and narrows the value type.
 *
 * @author Ardenexal
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
class FHIRExtensionDefinition
{
    /**
     * @param string $url         The canonical URL of the extension StructureDefinition
     * @param string $fhirVersion The FHIR version (e.g., 'R4', 'R4B', 'R5')
     */
    public function __construct(
        public readonly string $url,
        public readonly string $fhirVersion,
    ) {
    }
}
