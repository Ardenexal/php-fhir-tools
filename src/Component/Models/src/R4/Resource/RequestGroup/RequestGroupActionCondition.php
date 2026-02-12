<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\RequestGroup;

/**
 * @description An expression that describes applicability criteria, or start/stop conditions for the action.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'RequestGroup', elementPath: 'RequestGroup.action.condition', fhirVersion: 'R4')]
class RequestGroupActionCondition extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\ActionConditionKindType kind applicability | start | stop */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\ActionConditionKindType $kind = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Expression expression Boolean-valued expression */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Expression $expression = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
