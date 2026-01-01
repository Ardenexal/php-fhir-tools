<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @description Identifies a concept from an external specification that roughly corresponds to this element.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'ElementDefinition.mapping', fhirVersion: 'R5')]
class FHIRElementDefinitionMapping extends FHIRElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRId identity Reference to mapping declaration */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRId $identity = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMimeTypesType language Computable language of mapping */
		public ?FHIRMimeTypesType $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string map Details of the mapping */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $map = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown comment Comments about the mapping or its use */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown $comment = null,
	) {
		parent::__construct($id, $extension);
	}
}
