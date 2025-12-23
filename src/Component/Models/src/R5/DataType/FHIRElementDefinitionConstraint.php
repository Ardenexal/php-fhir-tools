<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-complex-type ElementDefinition.constraint
 * @description Formal constraints such as co-occurrence and other constraints that can be computationally evaluated within the context of the instance.
 */
class FHIRElementDefinitionConstraint extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRId key Target of 'condition' reference above */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRId $key = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown requirements Why this constraint is necessary or appropriate */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown $requirements = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRConstraintSeverityType severity error | warning */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRConstraintSeverityType $severity = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean suppress Suppress warning or hint in profile */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean $suppress = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string human Human description of constraint */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $human = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string expression FHIRPath expression of constraint */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $expression = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical source Reference to original source of constraint */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical $source = null,
	) {
		parent::__construct($id, $extension);
	}
}
