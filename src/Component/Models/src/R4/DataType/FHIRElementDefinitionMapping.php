<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-complex-type ElementDefinition.mapping
 * @description Identifies a concept from an external specification that roughly corresponds to this element.
 */
class FHIRElementDefinitionMapping extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRId identity Reference to mapping declaration */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRId $identity = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMimeTypesType language Computable language of mapping */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMimeTypesType $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string map Details of the mapping */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|null $map = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string comment Comments about the mapping or its use */
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|null $comment = null,
	) {
		parent::__construct($id, $extension);
	}
}
