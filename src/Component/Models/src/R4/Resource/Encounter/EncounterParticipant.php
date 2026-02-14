<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Encounter;

/**
 * @description The list of people responsible for providing the service.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Encounter', elementPath: 'Encounter.participant', fhirVersion: 'R4')]
class EncounterParticipant extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> type Role of participant in encounter */
		public array $type = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period period Period of time during the encounter that the participant participated */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period $period = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference individual Persons involved in the encounter other than the patient */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $individual = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
