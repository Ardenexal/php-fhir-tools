<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @author Health Level Seven International (Patient Administration)
 * @see http://hl7.org/fhir/StructureDefinition/AppointmentResponse
 * @description A reply to an appointment request for a patient and/or practitioner(s), such as a confirmation or rejection.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'AppointmentResponse',
	version: '4.3.0',
	url: 'http://hl7.org/fhir/StructureDefinition/AppointmentResponse',
	fhirVersion: 'R4B',
)]
class FHIRAppointmentResponse extends FHIRDomainResource
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRIdentifier> identifier External Ids for this item */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference appointment Appointment this response relates to */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRReference $appointment = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRInstant start Time from appointment, or requested new start time */
		public ?FHIRInstant $start = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRInstant end Time from appointment, or requested new end time */
		public ?FHIRInstant $end = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept> participantType Role of participant in the appointment */
		public array $participantType = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference actor Person, Location, HealthcareService, or Device */
		public ?FHIRReference $actor = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRParticipationStatusType participantStatus accepted | declined | tentative | needs-action */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRParticipationStatusType $participantStatus = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string comment Additional comments */
		public FHIRString|string|null $comment = null,
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
