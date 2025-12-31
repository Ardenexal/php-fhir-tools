<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @description Formal constraints such as co-occurrence and other constraints that can be computationally evaluated within the context of the instance.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'ElementDefinition.constraint', fhirVersion: 'R4B')]
class FHIRElementDefinitionConstraint extends FHIRElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRId key Target of 'condition' reference above */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRId $key = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string requirements Why this constraint is necessary or appropriate */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $requirements = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRConstraintSeverityType severity error | warning */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRConstraintSeverityType $severity = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string human Human description of constraint */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $human = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string expression FHIRPath expression of constraint */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $expression = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string xpath XPath expression of constraint */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $xpath = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRCanonical source Reference to original source of constraint */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRCanonical $source = null,
	) {
		parent::__construct($id, $extension);
	}
}
