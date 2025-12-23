<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element AdverseEvent.suspectEntity.causality
 * @description Information on the possible cause of the event.
 */
class FHIRAdverseEventSuspectEntityCausality extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept assessment Assessment of if the entity caused the event */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept $assessment = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string productRelatedness AdverseEvent.suspectEntity.causalityProductRelatedness */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $productRelatedness = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference author AdverseEvent.suspectEntity.causalityAuthor */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference $author = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept method ProbabilityScale | Bayesian | Checklist */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept $method = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
