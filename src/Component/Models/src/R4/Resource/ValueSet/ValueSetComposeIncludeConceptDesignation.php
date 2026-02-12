<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ValueSet;

/**
 * @description Additional representations for this concept when used in this value set - other languages, aliases, specialized purposes, used for particular purposes, etc.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ValueSet', elementPath: 'ValueSet.compose.include.concept.designation', fhirVersion: 'R4')]
class ValueSetComposeIncludeConceptDesignation extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|string language Human language of the designation */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding use Types of uses of designations */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding $use = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string value The text value for this designation */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $value = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
