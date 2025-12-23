<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-backbone-element RiskAssessment.prediction
 * @description Describes the expected outcome for the subject.
 */
class FHIRRiskAssessmentPrediction extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept outcome Possible outcome for the subject */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept $outcome = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDecimal|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRRange probabilityX Likelihood of specified outcome */
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDecimal|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRRange|null $probabilityX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept qualitativeRisk Likelihood of specified outcome as a qualitative value */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept $qualitativeRisk = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDecimal relativeRisk Relative likelihood */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDecimal $relativeRisk = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRPeriod|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRRange whenX Timeframe or age range */
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRPeriod|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRRange|null $whenX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string rationale Explanation of prediction */
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|null $rationale = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
