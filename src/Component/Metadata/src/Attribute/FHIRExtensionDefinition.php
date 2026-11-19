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
     * @param string      $url              The canonical URL of the extension StructureDefinition
     * @param string      $fhirVersion      The FHIR version (e.g., 'R4', 'R4B', 'R5')
     * @param bool        $isModifier       Whether the definition's root `Extension` element is a modifier, so the
     *                                      extension belongs in `modifierExtension` and never in `extension`
     * @param string|null $startFhirVersion The earliest FHIR version the extension may be used in, from the
     *                                      definition's `version-specific-use` extension (e.g. '4.0'); null for no limit
     * @param string|null $endFhirVersion   The latest FHIR version the extension may be used in (e.g. '4.3'); null for no limit
     */
    public function __construct(
        public readonly string $url,
        public readonly string $fhirVersion,
        public readonly bool $isModifier = false,
        public readonly ?string $startFhirVersion = null,
        public readonly ?string $endFhirVersion = null,
    ) {
    }
}
