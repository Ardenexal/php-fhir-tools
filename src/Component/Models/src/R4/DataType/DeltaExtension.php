<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @author Health Level Seven, Inc. - OO WG
 *
 * @see http://hl7.org/fhir/StructureDefinition/observation-delta
 *
 * @description The qualitative change in the value relative to the previous measurement. Usually only recorded if the change is clinically significant.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/observation-delta', fhirVersion: 'R4')]
class DeltaExtension extends Extension
{
    public function __construct(
        /** @var CodeableConcept|null valueCodeableConcept Value of extension */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $valueCodeableConcept = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/observation-delta',
            value: $this->valueCodeableConcept,
        );
    }
}
