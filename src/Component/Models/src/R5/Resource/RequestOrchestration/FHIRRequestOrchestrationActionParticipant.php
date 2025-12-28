<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description The participant that should perform or be responsible for this action.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'RequestOrchestration', elementPath: 'RequestOrchestration.action.participant', fhirVersion: 'R5')]
class FHIRRequestOrchestrationActionParticipant extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRActionParticipantTypeType type careteam | device | group | healthcareservice | location | organization | patient | practitioner | practitionerrole | relatedperson */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRActionParticipantTypeType $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical typeCanonical Who or what can participate */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical $typeCanonical = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference typeReference Who or what can participate */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $typeReference = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept role E.g. Nurse, Surgeon, Parent, etc */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $role = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept function E.g. Author, Reviewer, Witness, etc */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $function = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference actorX Who/what is participating? */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference|null $actorX = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
