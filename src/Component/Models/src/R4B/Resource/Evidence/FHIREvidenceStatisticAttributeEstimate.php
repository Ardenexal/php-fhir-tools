<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @description A statistical attribute of the statistic such as a measure of heterogeneity.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Evidence', elementPath: 'Evidence.statistic.attributeEstimate', fhirVersion: 'R4B')]
class FHIREvidenceStatisticAttributeEstimate extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string description Textual description of the attribute estimate */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $description = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRAnnotation> note Footnote or explanatory note about the estimate */
		public array $note = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept type The type of attribute estimate, eg confidence interval or p value */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRQuantity quantity The singular quantity of the attribute estimate, for attribute estimates represented as single values; also used to report unit of measure */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRQuantity $quantity = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDecimal level Level of confidence interval, eg 0.95 for 95% confidence interval */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDecimal $level = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRange range Lower and upper bound values of the attribute estimate */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRange $range = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIREvidenceStatisticAttributeEstimate> attributeEstimate A nested attribute estimate; which is the attribute estimate of an attribute estimate */
		public array $attributeEstimate = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
