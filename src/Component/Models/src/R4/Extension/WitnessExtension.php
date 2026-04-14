<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @author Health Level Seven, Inc. - CBCC WG
 *
 * @see http://hl7.org/fhir/StructureDefinition/consent-Witness
 *
 * @description Any witness to the consent.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/consent-Witness', fhirVersion: 'R4')]
class WitnessExtension extends Extension
{
    public function __construct(
        /** @var Reference|null valueReference Value of extension */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $valueReference = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/consent-Witness',
            value: $this->valueReference,
        );
    }
}
