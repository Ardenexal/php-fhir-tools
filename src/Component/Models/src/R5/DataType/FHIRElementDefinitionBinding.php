<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-complex-type ElementDefinition.binding
 * @description Binds to a value set if this element is coded (code, Coding, CodeableConcept, Quantity), or the data types (string, uri).
 */
class FHIRElementDefinitionBinding extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBindingStrengthType strength required | extensible | preferred | example */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBindingStrengthType $strength = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown description Intended use of codes in the bound value set */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical valueSet Source of value set */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical $valueSet = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRElementDefinitionBindingAdditional> additional Additional Bindings - more rules about the binding */
		public array $additional = [],
	) {
		parent::__construct($id, $extension);
	}
}
