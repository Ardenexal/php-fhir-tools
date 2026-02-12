<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicationKnowledge;

/**
 * @description Guidelines for the administration of the medication.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'MedicationKnowledge', elementPath: 'MedicationKnowledge.administrationGuidelines', fhirVersion: 'R4')]
class MedicationKnowledgeAdministrationGuidelines extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicationKnowledge\MedicationKnowledgeAdministrationGuidelinesDosage> dosage Dosage for the medication for the specific guidelines */
		public array $dosage = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference indicationX Indication for use that apply to the specific administration guidelines */
		public \Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference|null $indicationX = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicationKnowledge\MedicationKnowledgeAdministrationGuidelinesPatientCharacteristics> patientCharacteristics Characteristics of the patient that are relevant to the administration guidelines */
		public array $patientCharacteristics = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
