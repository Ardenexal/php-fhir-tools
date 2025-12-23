<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Pharmacy)
 * @see http://hl7.org/fhir/StructureDefinition/MedicationAdministration
 * @description Describes the event of a patient consuming or otherwise being administered a medication.  This may be as simple as swallowing a tablet or it may be a long running infusion.  Related resources tie this event to the authorizing prescription, and the specific encounter between patient and health care practitioner.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'MedicationAdministration',
	version: '4.0.1',
	url: 'http://hl7.org/fhir/StructureDefinition/MedicationAdministration',
	fhirVersion: 'R4',
)]
class FHIRMedicationAdministration extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMeta meta Metadata about the resource */
		public ?FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRUri implicitRules A set of rules under which this content was created */
		public ?FHIRUri $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRIdentifier> identifier External identifier */
		public array $identifier = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRUri> instantiates Instantiates protocol or definition */
		public array $instantiates = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference> partOf Part of referenced event */
		public array $partOf = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMedicationAdministrationStatusCodesType status in-progress | not-done | on-hold | completed | entered-in-error | stopped | unknown */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRMedicationAdministrationStatusCodesType $status = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept> statusReason Reason administration not performed */
		public array $statusReason = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept category Type of medication usage */
		public ?FHIRCodeableConcept $category = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference medicationX What was administered */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public FHIRCodeableConcept|FHIRReference|null $medicationX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference subject Who received medication */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRReference $subject = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference context Encounter or Episode of Care administered as part of */
		public ?FHIRReference $context = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference> supportingInformation Additional information to support administration */
		public array $supportingInformation = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRPeriod effectiveX Start and end time of administration */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public FHIRDateTime|FHIRPeriod|null $effectiveX = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMedicationAdministrationPerformer> performer Who performed the medication administration and what they did */
		public array $performer = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept> reasonCode Reason administration performed */
		public array $reasonCode = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference> reasonReference Condition or observation that supports why the medication was administered */
		public array $reasonReference = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference request Request administration performed against */
		public ?FHIRReference $request = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference> device Device used to administer */
		public array $device = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRAnnotation> note Information about the administration */
		public array $note = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMedicationAdministrationDosage dosage Details of how medication was taken */
		public ?FHIRMedicationAdministrationDosage $dosage = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference> eventHistory A list of events of interest in the lifecycle */
		public array $eventHistory = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
