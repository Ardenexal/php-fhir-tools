<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\PlanDefinition;

/**
 * @description A relationship to another action such as "before" or "30-60 minutes after start of".
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'PlanDefinition', elementPath: 'PlanDefinition.action.relatedAction', fhirVersion: 'R4')]
class PlanDefinitionActionRelatedAction extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\IdPrimitive actionId What action is this related to */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\IdPrimitive $actionId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\ActionRelationshipTypeType relationship before-start | before | before-end | concurrent-with-start | concurrent | concurrent-with-end | after-start | after | after-end */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\ActionRelationshipTypeType $relationship = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Duration|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Range offsetX Time offset for the relationship */
		public \Ardenexal\FHIRTools\Component\Models\R4\DataType\Duration|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Range|null $offsetX = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
