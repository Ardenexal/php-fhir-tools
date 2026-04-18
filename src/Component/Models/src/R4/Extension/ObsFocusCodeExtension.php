<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @author HL7 International / Orders and Observations
 *
 * @see http://hl7.org/fhir/StructureDefinition/observation-focusCode
 *
 * @description This extension is deprecated. There are alternate ways to represent this information through the use of Observation.focus and the elements of the referenced Resource. Old description: A code representing the  focus of an observation when the focus is not the patient of record.  In other words, the focus of the observation is different from `Observation.subject`.   An example use case would be using the *Observation* resource to capture whether the mother is trained to change her child's tracheostomy tube.  In this example, the child is the patient of record and the mother is focal subject referenced using this extension.  Other example focal subjects include spouses, related persons, feti, or  donors.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/observation-focusCode', fhirVersion: 'R4')]
class ObsFocusCodeExtension extends Extension
{
    public function __construct(
        /** @var CodeableConcept|null valueCodeableConcept Value of extension */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $valueCodeableConcept = null,
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
