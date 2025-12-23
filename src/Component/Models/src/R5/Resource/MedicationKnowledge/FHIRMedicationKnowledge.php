<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (Pharmacy)
 * @see http://hl7.org/fhir/StructureDefinition/MedicationKnowledge
 * @description Information about a medication that is used to support knowledge.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'MedicationKnowledge',
	version: '5.0.0',
	url: 'http://hl7.org/fhir/StructureDefinition/MedicationKnowledge',
	fhirVersion: 'R5',
)]
class FHIRMedicationKnowledge extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMeta meta Metadata about the resource */
		public ?FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri implicitRules A set of rules under which this content was created */
		public ?FHIRUri $implicitRules = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAllLanguagesType language Language of the resource content */
		public ?FHIRAllLanguagesType $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier> identifier Business identifier for this medication */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept code Code that identifies this medication */
		public ?FHIRCodeableConcept $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMedicationKnowledgeStatusCodesType status active | entered-in-error | inactive */
		public ?FHIRMedicationKnowledgeStatusCodesType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference author Creator or owner of the knowledge or information about the medication */
		public ?FHIRReference $author = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> intendedJurisdiction Codes that identify the different jurisdictions for which the information of this resource was created */
		public array $intendedJurisdiction = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string> name A name associated with the medication being described */
		public array $name = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMedicationKnowledgeRelatedMedicationKnowledge> relatedMedicationKnowledge Associated or related medication information */
		public array $relatedMedicationKnowledge = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> associatedMedication The set of medication resources that are associated with this medication */
		public array $associatedMedication = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> productType Category of the medication or product */
		public array $productType = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMedicationKnowledgeMonograph> monograph Associated documentation about the medication */
		public array $monograph = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown preparationInstruction The instructions for preparing the medication */
		public ?FHIRMarkdown $preparationInstruction = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMedicationKnowledgeCost> cost The pricing of the medication */
		public array $cost = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMedicationKnowledgeMonitoringProgram> monitoringProgram Program under which a medication is reviewed */
		public array $monitoringProgram = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMedicationKnowledgeIndicationGuideline> indicationGuideline Guidelines or protocols for administration of the medication for an indication */
		public array $indicationGuideline = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMedicationKnowledgeMedicineClassification> medicineClassification Categorization of the medication within a formulary or classification system */
		public array $medicineClassification = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMedicationKnowledgePackaging> packaging Details about packaged medications */
		public array $packaging = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> clinicalUseIssue Potential clinical issue with or between medication(s) */
		public array $clinicalUseIssue = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMedicationKnowledgeStorageGuideline> storageGuideline How the medication should be stored */
		public array $storageGuideline = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMedicationKnowledgeRegulatory> regulatory Regulatory information about a medication */
		public array $regulatory = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMedicationKnowledgeDefinitional definitional Minimal definition information about the medication */
		public ?FHIRMedicationKnowledgeDefinitional $definitional = null,
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
