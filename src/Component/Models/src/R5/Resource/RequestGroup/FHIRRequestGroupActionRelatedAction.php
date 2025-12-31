<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description A relationship to another action such as "before" or "30-60 minutes after start of".
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'RequestGroup', elementPath: 'RequestGroup.action.relatedAction', fhirVersion: 'R5')]
class FHIRRequestGroupActionRelatedAction extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRId actionId What action this is related to */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRId $actionId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRActionRelationshipTypeType relationship before-start | before | before-end | concurrent-with-start | concurrent | concurrent-with-end | after-start | after | after-end */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRActionRelationshipTypeType $relationship = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRDuration|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRange offsetX Time offset for the relationship */
		public \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRDuration|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRange|null $offsetX = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
