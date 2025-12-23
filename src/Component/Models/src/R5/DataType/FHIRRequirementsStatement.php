<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element Requirements.statement
 * @description The actual statement of requirement, in markdown format.
 */
class FHIRRequirementsStatement extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRId key Key that identifies this statement */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRId $key = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string label Short Human label for this statement */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $label = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRConformanceExpectationType> conformance SHALL | SHOULD | MAY | SHOULD-NOT */
		public array $conformance = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean conditionality Set to true if requirements statement is conditional */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean $conditionality = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown requirement The actual requirement */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown $requirement = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string derivedFrom Another statement this clarifies/restricts ([url#]key) */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $derivedFrom = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string parent A larger requirement that this requirement helps to refine and enable */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $parent = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUrl> satisfiedBy Design artifact that satisfies this requirement */
		public array $satisfiedBy = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUrl> reference External artifact (rule/document etc. that) created this requirement */
		public array $reference = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> source Who asked for this statement */
		public array $source = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
