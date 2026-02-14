<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Pharmacy)
 * @see http://hl7.org/fhir/StructureDefinition/MedicationKnowledge
 * @description Information about a medication that is used to support knowledge.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'MedicationKnowledge',
	version: '4.0.1',
	url: 'http://hl7.org/fhir/StructureDefinition/MedicationKnowledge',
	fhirVersion: 'R4',
)]
class MedicationKnowledgeResource extends DomainResourceResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta meta Metadata about the resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive implicitRules A set of rules under which this content was created */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative text Text summary of the resource, for human interpretation */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ResourceResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept code Code that identifies this medication */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\MedicationKnowledgeStatusCodesType status active | inactive | entered-in-error */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\MedicationKnowledgeStatusCodesType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference manufacturer Manufacturer of the item */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $manufacturer = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept doseForm powder | tablets | capsule + */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $doseForm = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity amount Amount of drug in package */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity $amount = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string> synonym Additional names for a medication */
		public array $synonym = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicationKnowledge\MedicationKnowledgeRelatedMedicationKnowledge> relatedMedicationKnowledge Associated or related medication information */
		public array $relatedMedicationKnowledge = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> associatedMedication A medication resource that is associated with this medication */
		public array $associatedMedication = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> productType Category of the medication or product */
		public array $productType = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicationKnowledge\MedicationKnowledgeMonograph> monograph Associated documentation about the medication */
		public array $monograph = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicationKnowledge\MedicationKnowledgeIngredient> ingredient Active or inactive ingredient */
		public array $ingredient = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive preparationInstruction The instructions for preparing the medication */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive $preparationInstruction = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> intendedRoute The intended or approved route of administration */
		public array $intendedRoute = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicationKnowledge\MedicationKnowledgeCost> cost The pricing of the medication */
		public array $cost = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicationKnowledge\MedicationKnowledgeMonitoringProgram> monitoringProgram Program under which a medication is reviewed */
		public array $monitoringProgram = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicationKnowledge\MedicationKnowledgeAdministrationGuidelines> administrationGuidelines Guidelines for administration of the medication */
		public array $administrationGuidelines = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicationKnowledge\MedicationKnowledgeMedicineClassification> medicineClassification Categorization of the medication within a formulary or classification system */
		public array $medicineClassification = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicationKnowledge\MedicationKnowledgePackaging packaging Details about packaged medications */
		public ?MedicationKnowledge\MedicationKnowledgePackaging $packaging = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicationKnowledge\MedicationKnowledgeDrugCharacteristic> drugCharacteristic Specifies descriptive properties of the medicine */
		public array $drugCharacteristic = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> contraindication Potential clinical issue with or between medication(s) */
		public array $contraindication = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicationKnowledge\MedicationKnowledgeRegulatory> regulatory Regulatory information about a medication */
		public array $regulatory = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicationKnowledge\MedicationKnowledgeKinetics> kinetics The time course of drug absorption, distribution, metabolism and excretion of a medication from the body */
		public array $kinetics = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
