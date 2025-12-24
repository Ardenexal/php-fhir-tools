<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;

/**
 * @description The guidelines for the dosage of the medication for the indication.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(
    parentResource: 'MedicationKnowledge',
    elementPath: 'MedicationKnowledge.indicationGuideline.dosingGuideline',
    fhirVersion: 'R5',
)]
class FHIRMedicationKnowledgeIndicationGuidelineDosingGuideline extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null treatmentIntent Intention of the treatment */
        public ?FHIRCodeableConcept $treatmentIntent = null,
        /** @var array<FHIRMedicationKnowledgeIndicationGuidelineDosingGuidelineDosage> dosage Dosage for the medication for the specific guidelines */
        public array $dosage = [],
        /** @var FHIRCodeableConcept|null administrationTreatment Type of treatment the guideline applies to */
        public ?FHIRCodeableConcept $administrationTreatment = null,
        /** @var array<FHIRMedicationKnowledgeIndicationGuidelineDosingGuidelinePatientCharacteristic> patientCharacteristic Characteristics of the patient that are relevant to the administration guidelines */
        public array $patientCharacteristic = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
