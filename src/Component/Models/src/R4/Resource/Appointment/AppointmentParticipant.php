<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Appointment;

/**
 * @description List of participants involved in the appointment.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Appointment', elementPath: 'Appointment.participant', fhirVersion: 'R4')]
class AppointmentParticipant extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> type Role of participant in the appointment */
		public array $type = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference actor Person, Location/HealthcareService or Device */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $actor = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\ParticipantRequiredType required required | optional | information-only */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\ParticipantRequiredType $required = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\ParticipationStatusType status accepted | declined | tentative | needs-action */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\ParticipationStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period period Participation period of the actor */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period $period = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
