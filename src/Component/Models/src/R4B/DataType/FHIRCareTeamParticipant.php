<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element CareTeam.participant
 * @description Identifies all people and organizations who are expected to be involved in the care team.
 */
class FHIRCareTeamParticipant extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept> role Type of involvement */
		public array $role = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference member Who is involved */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference $member = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference onBehalfOf Organization of the practitioner */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference $onBehalfOf = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRPeriod period Time period of participant */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRPeriod $period = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
