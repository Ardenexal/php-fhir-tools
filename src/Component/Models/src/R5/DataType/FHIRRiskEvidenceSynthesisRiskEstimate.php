<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element RiskEvidenceSynthesis.riskEstimate
 * @description The estimated risk of the outcome.
 */
class FHIRRiskEvidenceSynthesisRiskEstimate extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string description Description of risk estimate */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept type Type of risk estimate */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDecimal value Point estimate */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDecimal $value = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept unitOfMeasure What unit is the outcome described in? */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $unitOfMeasure = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRInteger denominatorCount Sample size for group measured */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRInteger $denominatorCount = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRInteger numeratorCount Number with the outcome */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRInteger $numeratorCount = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRiskEvidenceSynthesisRiskEstimatePrecisionEstimate> precisionEstimate How precise the estimate is */
		public array $precisionEstimate = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
