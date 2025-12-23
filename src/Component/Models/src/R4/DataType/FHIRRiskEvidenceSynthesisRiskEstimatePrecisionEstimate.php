<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-backbone-element RiskEvidenceSynthesis.riskEstimate.precisionEstimate
 * @description A description of the precision of the estimate for the effect.
 */
class FHIRRiskEvidenceSynthesisRiskEstimatePrecisionEstimate extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept type Type of precision estimate */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDecimal level Level of confidence interval */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDecimal $level = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDecimal from Lower bound */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDecimal $from = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDecimal to Upper bound */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDecimal $to = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
