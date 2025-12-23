<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element MedicationKnowledge.indicationGuideline.dosingGuideline
 * @description The guidelines for the dosage of the medication for the indication.
 */
class FHIRMedicationKnowledgeIndicationGuidelineDosingGuideline extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept treatmentIntent Intention of the treatment */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $treatmentIntent = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMedicationKnowledgeIndicationGuidelineDosingGuidelineDosage> dosage Dosage for the medication for the specific guidelines */
		public array $dosage = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept administrationTreatment Type of treatment the guideline applies to */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $administrationTreatment = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMedicationKnowledgeIndicationGuidelineDosingGuidelinePatientCharacteristic> patientCharacteristic Characteristics of the patient that are relevant to the administration guidelines */
		public array $patientCharacteristic = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
