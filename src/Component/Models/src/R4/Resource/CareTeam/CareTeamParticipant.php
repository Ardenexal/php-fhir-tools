<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\CareTeam;

/**
 * @description Identifies all people and organizations who are expected to be involved in the care team.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'CareTeam', elementPath: 'CareTeam.participant', fhirVersion: 'R4')]
class CareTeamParticipant extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> role Type of involvement */
		public array $role = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference member Who is involved */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $member = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference onBehalfOf Organization of the practitioner */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $onBehalfOf = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period period Time period of participant */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period $period = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
