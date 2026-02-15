<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicationKnowledge;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;

/**
 * @description Guidelines for the administration of the medication.
 */
#[FHIRBackboneElement(parentResource: 'MedicationKnowledge', elementPath: 'MedicationKnowledge.administrationGuidelines', fhirVersion: 'R4')]
class MedicationKnowledgeAdministrationGuidelines extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<MedicationKnowledgeAdministrationGuidelinesDosage> dosage Dosage for the medication for the specific guidelines */
        public array $dosage = [],
        /** @var CodeableConcept|Reference|null indicationX Indication for use that apply to the specific administration guidelines */
        public CodeableConcept|Reference|null $indicationX = null,
        /** @var array<MedicationKnowledgeAdministrationGuidelinesPatientCharacteristics> patientCharacteristics Characteristics of the patient that are relevant to the administration guidelines */
        public array $patientCharacteristics = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
