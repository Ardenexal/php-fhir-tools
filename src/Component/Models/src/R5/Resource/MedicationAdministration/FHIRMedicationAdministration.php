<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (Pharmacy)
 * @see http://hl7.org/fhir/StructureDefinition/MedicationAdministration
 * @description Describes the event of a patient consuming or otherwise being administered a medication.  This may be as simple as swallowing a tablet or it may be a long running infusion.  Related resources tie this event to the authorizing prescription, and the specific encounter between patient and health care practitioner.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'MedicationAdministration',
	version: '5.0.0',
	url: 'http://hl7.org/fhir/StructureDefinition/MedicationAdministration',
	fhirVersion: 'R5',
)]
class FHIRMedicationAdministration extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta meta Metadata about the resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri implicitRules A set of rules under which this content was created */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri $implicitRules = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAllLanguagesType language Language of the resource content */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAllLanguagesType $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier> identifier External identifier */
		public array $identifier = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference> basedOn Plan this is fulfilled by this administration */
		public array $basedOn = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference> partOf Part of referenced event */
		public array $partOf = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMedicationAdministrationStatusCodesType status in-progress | not-done | on-hold | completed | entered-in-error | stopped | unknown */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMedicationAdministrationStatusCodesType $status = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept> statusReason Reason administration not performed */
		public array $statusReason = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept> category Type of medication administration */
		public array $category = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference medication What was administered */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference $medication = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference subject Who received medication */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $subject = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference encounter Encounter administered as part of */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $encounter = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference> supportingInformation Additional information to support administration */
		public array $supportingInformation = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRTiming occurenceX Specific date/time or interval of time during which the administration took place (or did not take place) */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRTiming|null $occurenceX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime recorded When the MedicationAdministration was first captured in the subject's record */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime $recorded = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean isSubPotent Full dose was not administered */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean $isSubPotent = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept> subPotentReason Reason full dose was not administered */
		public array $subPotentReason = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMedicationAdministrationPerformer> performer Who or what performed the medication administration and what type of performance they did */
		public array $performer = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference> reason Concept, condition or observation that supports why the medication was administered */
		public array $reason = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference request Request administration performed against */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $request = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference> device Device used to administer */
		public array $device = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAnnotation> note Information about the administration */
		public array $note = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMedicationAdministrationDosage dosage Details of how medication was taken */
		public ?FHIRMedicationAdministrationDosage $dosage = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference> eventHistory A list of events of interest in the lifecycle */
		public array $eventHistory = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
