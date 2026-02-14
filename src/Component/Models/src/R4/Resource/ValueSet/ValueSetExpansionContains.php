<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ValueSet;

/**
 * @description The codes that are contained in the value set expansion.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ValueSet', elementPath: 'ValueSet.expansion.contains', fhirVersion: 'R4')]
class ValueSetExpansionContains extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive system System value for the code */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive $system = null,
		/** @var null|bool abstract If user cannot select this entry */
		public ?bool $abstract = null,
		/** @var null|bool inactive If concept is inactive in the code system */
		public ?bool $inactive = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string version Version in which this code/display is defined */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $version = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive code Code - if blank, this is not a selectable code */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string display User display for the concept */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $display = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ValueSet\ValueSetComposeIncludeConceptDesignation> designation Additional representations for this item */
		public array $designation = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ValueSet\ValueSetExpansionContains> contains Codes contained under this entry */
		public array $contains = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
