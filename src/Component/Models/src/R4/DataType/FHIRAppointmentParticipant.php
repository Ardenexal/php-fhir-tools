<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-backbone-element Appointment.participant
 * @description List of participants involved in the appointment.
 */
class FHIRAppointmentParticipant extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept> type Role of participant in the appointment */
		public array $type = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference actor Person, Location/HealthcareService or Device */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference $actor = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRParticipantRequiredType required required | optional | information-only */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRParticipantRequiredType $required = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRParticipationStatusType status accepted | declined | tentative | needs-action */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRParticipationStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRPeriod period Participation period of the actor */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRPeriod $period = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
