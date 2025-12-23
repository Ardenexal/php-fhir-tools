<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element Evidence.statistic.attributeEstimate
 * @description A statistical attribute of the statistic such as a measure of heterogeneity.
 */
class FHIREvidenceStatisticAttributeEstimate extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown description Textual description of the attribute estimate */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown $description = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAnnotation> note Footnote or explanatory note about the estimate */
		public array $note = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept type The type of attribute estimate, e.g., confidence interval or p value */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRQuantity quantity The singular quantity of the attribute estimate, for attribute estimates represented as single values; also used to report unit of measure */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRQuantity $quantity = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDecimal level Level of confidence interval, e.g., 0.95 for 95% confidence interval */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDecimal $level = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRange range Lower and upper bound values of the attribute estimate */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRange $range = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIREvidenceStatisticAttributeEstimate> attributeEstimate A nested attribute estimate; which is the attribute estimate of an attribute estimate */
		public array $attributeEstimate = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
