<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @author HL7 International / Clinical Decision Support
 *
 * @see http://hl7.org/fhir/StructureDefinition/evidence-variable-handling-detail
 *
 * @description This extension is used when EvidenceVariable.handling has a value of ‘extension’ because the handling element has a required binding and an extension is needed when the handling cannot be described with any of the other values in the limited value set.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/evidence-variable-handling-detail', fhirVersion: 'R4')]
class EvidenceVariableHandlingDetailExtension extends Extension
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
            url: 'http://hl7.org/fhir/StructureDefinition/evidence-variable-handling-detail',
            value: $this->valueCodeableConcept,
        );
    }
}
