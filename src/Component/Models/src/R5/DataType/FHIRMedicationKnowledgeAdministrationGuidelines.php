<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element MedicationKnowledge.administrationGuidelines
 * @description Guidelines for the administration of the medication.
 */
class FHIRMedicationKnowledgeAdministrationGuidelines extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMedicationKnowledgeAdministrationGuidelinesDosage> dosage Dosage for the medication for the specific guidelines */
		public array $dosage = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference indicationX Indication for use that apply to the specific administration guidelines */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference|null $indicationX = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMedicationKnowledgeAdministrationGuidelinesPatientCharacteristics> patientCharacteristics Characteristics of the patient that are relevant to the administration guidelines */
		public array $patientCharacteristics = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
