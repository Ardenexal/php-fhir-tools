<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\AdverseEvent;

/**
 * @description Information on the possible cause of the event.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'AdverseEvent', elementPath: 'AdverseEvent.suspectEntity.causality', fhirVersion: 'R4')]
class AdverseEventSuspectEntityCausality extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept assessment Assessment of if the entity caused the event */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $assessment = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string productRelatedness AdverseEvent.suspectEntity.causalityProductRelatedness */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $productRelatedness = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference author AdverseEvent.suspectEntity.causalityAuthor */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $author = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept method ProbabilityScale | Bayesian | Checklist */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $method = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
