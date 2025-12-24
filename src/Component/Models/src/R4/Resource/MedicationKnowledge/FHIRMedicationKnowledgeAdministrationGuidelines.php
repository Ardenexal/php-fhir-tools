<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference;

/**
 * @description Guidelines for the administration of the medication.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'MedicationKnowledge', elementPath: 'MedicationKnowledge.administrationGuidelines', fhirVersion: 'R4')]
class FHIRMedicationKnowledgeAdministrationGuidelines extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<FHIRMedicationKnowledgeAdministrationGuidelinesDosage> dosage Dosage for the medication for the specific guidelines */
        public array $dosage = [],
        /** @var FHIRCodeableConcept|FHIRReference|null indicationX Indication for use that apply to the specific administration guidelines */
        public FHIRCodeableConcept|FHIRReference|null $indicationX = null,
        /** @var array<FHIRMedicationKnowledgeAdministrationGuidelinesPatientCharacteristics> patientCharacteristics Characteristics of the patient that are relevant to the administration guidelines */
        public array $patientCharacteristics = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
