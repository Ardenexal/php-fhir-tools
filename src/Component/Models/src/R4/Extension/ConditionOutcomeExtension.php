<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @author HL7 International / Patient Care
 *
 * @see http://hl7.org/fhir/StructureDefinition/condition-outcome
 *
 * @description A result of the condition. The "Cause of death" for a patient is typically captured as an Observation.  The "outcome" doesn't imply causality.  Some outcomes might not be assessable until the condition.clinicalStatus is no longer active.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/condition-outcome', fhirVersion: 'R4')]
class ConditionOutcomeExtension extends Extension
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
            url: 'http://hl7.org/fhir/StructureDefinition/condition-outcome',
            value: $this->valueCodeableConcept,
        );
    }
}
