<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (Patient Care)
 * @see http://hl7.org/fhir/StructureDefinition/Procedure
 * @description An action that is or was performed on or for a patient, practitioner, device, organization, or location. For example, this can be a physical intervention on a patient like an operation, or less invasive like long term services, counseling, or hypnotherapy.  This can be a quality or safety inspection for a location, organization, or device.  This can be an accreditation procedure on a practitioner for licensing.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'Procedure', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/Procedure', fhirVersion: 'R5')]
class FHIRProcedure extends FHIRDomainResource
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier> identifier External Identifiers for this procedure */
		public array $identifier = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical> instantiatesCanonical Instantiates FHIR protocol or definition */
		public array $instantiatesCanonical = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri> instantiatesUri Instantiates external protocol or definition */
		public array $instantiatesUri = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference> basedOn A request for this procedure */
		public array $basedOn = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference> partOf Part of referenced event */
		public array $partOf = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIREventStatusType status preparation | in-progress | not-done | on-hold | stopped | completed | entered-in-error | unknown */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIREventStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept statusReason Reason for current status */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $statusReason = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept> category Classification of the procedure */
		public array $category = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept code Identification of the procedure */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference subject Individual or entity the procedure was performed on */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $subject = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference focus Who is the target of the procedure when it is not the subject of record only */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $focus = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference encounter The Encounter during which this Procedure was created */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $encounter = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAge|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRange|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRTiming occurrenceX When the procedure occurred or is occurring */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAge|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRange|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRTiming|null $occurrenceX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime recorded When the procedure was first captured in the subject's record */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime $recorded = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference recorder Who recorded the procedure */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $recorder = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference reportedX Reported rather than primary record */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference|null $reportedX = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRProcedurePerformer> performer Who performed the procedure and what they did */
		public array $performer = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference location Where the procedure happened */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $location = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference> reason The justification that the procedure was performed */
		public array $reason = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept> bodySite Target body sites */
		public array $bodySite = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept outcome The result of procedure */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $outcome = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference> report Any report resulting from the procedure */
		public array $report = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference> complication Complication following the procedure */
		public array $complication = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept> followUp Instructions for follow up */
		public array $followUp = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAnnotation> note Additional information about the procedure */
		public array $note = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRProcedureFocalDevice> focalDevice Manipulated, implanted, or removed device */
		public array $focalDevice = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference> used Items used during procedure */
		public array $used = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference> supportingInfo Extra information relevant to the procedure */
		public array $supportingInfo = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
