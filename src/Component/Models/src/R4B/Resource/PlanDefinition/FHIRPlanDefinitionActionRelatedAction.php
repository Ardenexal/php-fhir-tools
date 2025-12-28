<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @description A relationship to another action such as "before" or "30-60 minutes after start of".
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'PlanDefinition', elementPath: 'PlanDefinition.action.relatedAction', fhirVersion: 'R4B')]
class FHIRPlanDefinitionActionRelatedAction extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRId actionId What action is this related to */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRId $actionId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRActionRelationshipTypeType relationship before-start | before | before-end | concurrent-with-start | concurrent | concurrent-with-end | after-start | after | after-end */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRActionRelationshipTypeType $relationship = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRDuration|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRange offsetX Time offset for the relationship */
		public \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRDuration|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRange|null $offsetX = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
