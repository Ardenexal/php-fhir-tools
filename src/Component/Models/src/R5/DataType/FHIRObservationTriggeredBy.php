<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element Observation.triggeredBy
 * @description Identifies the observation(s) that triggered the performance of this observation.
 */
class FHIRObservationTriggeredBy extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference observation Triggering observation */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference $observation = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRTriggeredBytypeType type reflex | repeat | re-run */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRTriggeredBytypeType $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string reason Reason that the observation was triggered */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $reason = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
