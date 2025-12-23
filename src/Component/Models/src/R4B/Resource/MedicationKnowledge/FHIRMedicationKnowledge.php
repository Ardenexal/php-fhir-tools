<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @author Health Level Seven International (Pharmacy)
 * @see http://hl7.org/fhir/StructureDefinition/MedicationKnowledge
 * @description Information about a medication that is used to support knowledge.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'MedicationKnowledge',
	version: '4.3.0',
	url: 'http://hl7.org/fhir/StructureDefinition/MedicationKnowledge',
	fhirVersion: 'R4B',
)]
class FHIRMedicationKnowledge extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMeta meta Metadata about the resource */
		public ?FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUri implicitRules A set of rules under which this content was created */
		public ?FHIRUri $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept code Code that identifies this medication */
		public ?FHIRCodeableConcept $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMedicationKnowledgeStatusCodesType status active | inactive | entered-in-error */
		public ?FHIRMedicationKnowledgeStatusCodesType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference manufacturer Manufacturer of the item */
		public ?FHIRReference $manufacturer = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept doseForm powder | tablets | capsule + */
		public ?FHIRCodeableConcept $doseForm = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantity amount Amount of drug in package */
		public ?FHIRQuantity $amount = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string> synonym Additional names for a medication */
		public array $synonym = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMedicationKnowledgeRelatedMedicationKnowledge> relatedMedicationKnowledge Associated or related medication information */
		public array $relatedMedicationKnowledge = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference> associatedMedication A medication resource that is associated with this medication */
		public array $associatedMedication = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept> productType Category of the medication or product */
		public array $productType = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMedicationKnowledgeMonograph> monograph Associated documentation about the medication */
		public array $monograph = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMedicationKnowledgeIngredient> ingredient Active or inactive ingredient */
		public array $ingredient = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMarkdown preparationInstruction The instructions for preparing the medication */
		public ?FHIRMarkdown $preparationInstruction = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept> intendedRoute The intended or approved route of administration */
		public array $intendedRoute = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMedicationKnowledgeCost> cost The pricing of the medication */
		public array $cost = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMedicationKnowledgeMonitoringProgram> monitoringProgram Program under which a medication is reviewed */
		public array $monitoringProgram = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMedicationKnowledgeAdministrationGuidelines> administrationGuidelines Guidelines for administration of the medication */
		public array $administrationGuidelines = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMedicationKnowledgeMedicineClassification> medicineClassification Categorization of the medication within a formulary or classification system */
		public array $medicineClassification = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMedicationKnowledgePackaging packaging Details about packaged medications */
		public ?FHIRMedicationKnowledgePackaging $packaging = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMedicationKnowledgeDrugCharacteristic> drugCharacteristic Specifies descriptive properties of the medicine */
		public array $drugCharacteristic = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference> contraindication Potential clinical issue with or between medication(s) */
		public array $contraindication = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMedicationKnowledgeRegulatory> regulatory Regulatory information about a medication */
		public array $regulatory = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMedicationKnowledgeKinetics> kinetics The time course of drug absorption, distribution, metabolism and excretion of a medication from the body */
		public array $kinetics = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
