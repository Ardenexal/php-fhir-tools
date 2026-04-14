<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author Health Level Seven, Inc. - OO WG
 *
 * @see http://hl7.org/fhir/StructureDefinition/observation-focusCode
 *
 * @description A code representing the  focus of an observation when the focus is not the patient of record.  In other words, the focus of the observation is different from `Observation.subject`.   An example use case would be using the *Observation* resource to capture whether the mother is trained to change her child's tracheostomy tube.  In this example, the child is the patient of record and the mother is focal subject referenced using this extension.  Other example focal subjects include spouses, related persons, feti, or  donors.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/observation-focusCode', fhirVersion: 'R4B')]
class FocusCodeExtension extends Extension
{
    public function __construct(
        /** @var CodeableConcept|null valueCodeableConcept Value of extension */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept $valueCodeableConcept = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/observation-focusCode',
            value: $this->valueCodeableConcept,
        );
    }
}
