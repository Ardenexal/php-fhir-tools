<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element PlanDefinition.action.relatedAction
 * @description A relationship to another action such as "before" or "30-60 minutes after start of".
 */
class FHIRPlanDefinitionActionRelatedAction extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRId targetId What action is this related to */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRId $targetId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRActionRelationshipTypeType relationship before | before-start | before-end | concurrent | concurrent-with-start | concurrent-with-end | after | after-start | after-end */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRActionRelationshipTypeType $relationship = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRActionRelationshipTypeType endRelationship before | before-start | before-end | concurrent | concurrent-with-start | concurrent-with-end | after | after-start | after-end */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRActionRelationshipTypeType $endRelationship = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDuration|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRange offsetX Time offset for the relationship */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDuration|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRange|null $offsetX = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
