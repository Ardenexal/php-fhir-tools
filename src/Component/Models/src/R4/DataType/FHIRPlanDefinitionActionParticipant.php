<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-backbone-element PlanDefinition.action.participant
 * @description Indicates who should participate in performing the action described.
 */
class FHIRPlanDefinitionActionParticipant extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRActionParticipantTypeType type patient | practitioner | related-person | device */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRActionParticipantTypeType $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept role E.g. Nurse, Surgeon, Parent */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept $role = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
