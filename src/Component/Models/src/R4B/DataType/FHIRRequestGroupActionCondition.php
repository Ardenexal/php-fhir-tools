<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element RequestGroup.action.condition
 * @description An expression that describes applicability criteria, or start/stop conditions for the action.
 */
class FHIRRequestGroupActionCondition extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRActionConditionKindType kind applicability | start | stop */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRActionConditionKindType $kind = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExpression expression Boolean-valued expression */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExpression $expression = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
