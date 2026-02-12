<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\RiskEvidenceSynthesis;

/**
 * @description A description of the precision of the estimate for the effect.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(
	parentResource: 'RiskEvidenceSynthesis',
	elementPath: 'RiskEvidenceSynthesis.riskEstimate.precisionEstimate',
	fhirVersion: 'R4',
)]
class RiskEvidenceSynthesisRiskEstimatePrecisionEstimate extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept type Type of precision estimate */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $type = null,
		/** @var null|float level Level of confidence interval */
		public ?float $level = null,
		/** @var null|float from Lower bound */
		public ?float $from = null,
		/** @var null|float to Upper bound */
		public ?float $to = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
