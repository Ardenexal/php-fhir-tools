<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @description List of participants involved in the appointment.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Appointment', elementPath: 'Appointment.participant', fhirVersion: 'R4B')]
class FHIRAppointmentParticipant extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept> type Role of participant in the appointment */
		public array $type = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference actor Person, Location/HealthcareService or Device */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference $actor = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRParticipantRequiredType required required | optional | information-only */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRParticipantRequiredType $required = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRParticipationStatusType status accepted | declined | tentative | needs-action */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRParticipationStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRPeriod period Participation period of the actor */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRPeriod $period = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
