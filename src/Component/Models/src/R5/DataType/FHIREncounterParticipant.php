<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element Encounter.participant
 * @description The list of people responsible for providing the service.
 */
class FHIREncounterParticipant extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> type Role of participant in encounter */
		public array $type = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPeriod period Period of time during the encounter that the participant participated */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPeriod $period = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference actor The individual, device, or service participating in the encounter */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference $actor = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
