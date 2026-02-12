<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ActivityDefinition;

/**
 * @description Indicates who should participate in performing the action described.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ActivityDefinition', elementPath: 'ActivityDefinition.participant', fhirVersion: 'R4')]
class ActivityDefinitionParticipant extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\ActionParticipantTypeType type patient | practitioner | related-person | device */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\ActionParticipantTypeType $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept role E.g. Nurse, Surgeon, Parent, etc. */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $role = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
