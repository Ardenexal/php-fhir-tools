<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-backbone-element AdverseEvent.suspectEntity
 * @description Describes the entity that is suspected to have caused the adverse event.
 */
class FHIRAdverseEventSuspectEntity extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference instance Refers to the specific entity that caused the adverse event */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference $instance = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRAdverseEventSuspectEntityCausality> causality Information on the possible cause of the event */
		public array $causality = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
