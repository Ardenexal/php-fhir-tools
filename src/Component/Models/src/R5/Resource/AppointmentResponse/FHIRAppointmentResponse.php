<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (Patient Administration)
 * @see http://hl7.org/fhir/StructureDefinition/AppointmentResponse
 * @description A reply to an appointment request for a patient and/or practitioner(s), such as a confirmation or rejection.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'AppointmentResponse',
	version: '5.0.0',
	url: 'http://hl7.org/fhir/StructureDefinition/AppointmentResponse',
	fhirVersion: 'R5',
)]
class FHIRAppointmentResponse extends FHIRDomainResource
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier> identifier External Ids for this item */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference appointment Appointment this response relates to */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRReference $appointment = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean proposedNewTime Indicator for a counter proposal */
		public ?FHIRBoolean $proposedNewTime = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRInstant start Time from appointment, or requested new start time */
		public ?FHIRInstant $start = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRInstant end Time from appointment, or requested new end time */
		public ?FHIRInstant $end = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> participantType Role of participant in the appointment */
		public array $participantType = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference actor Person(s), Location, HealthcareService, or Device */
		public ?FHIRReference $actor = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAppointmentResponseStatusType participantStatus accepted | declined | tentative | needs-action | entered-in-error */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRAppointmentResponseStatusType $participantStatus = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown comment Additional comments */
		public ?FHIRMarkdown $comment = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean recurring This response is for all occurrences in a recurring request */
		public ?FHIRBoolean $recurring = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDate occurrenceDate Original date within a recurring request */
		public ?FHIRDate $occurrenceDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPositiveInt recurrenceId The recurrence ID of the specific recurring request */
		public ?FHIRPositiveInt $recurrenceId = null,
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
