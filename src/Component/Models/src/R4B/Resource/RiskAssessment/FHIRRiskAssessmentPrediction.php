<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @description Describes the expected outcome for the subject.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'RiskAssessment', elementPath: 'RiskAssessment.prediction', fhirVersion: 'R4B')]
class FHIRRiskAssessmentPrediction extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept outcome Possible outcome for the subject */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept $outcome = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDecimal|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRange probabilityX Likelihood of specified outcome */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDecimal|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRange|null $probabilityX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept qualitativeRisk Likelihood of specified outcome as a qualitative value */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept $qualitativeRisk = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDecimal relativeRisk Relative likelihood */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDecimal $relativeRisk = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRPeriod|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRange whenX Timeframe or age range */
		public \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRPeriod|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRange|null $whenX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string rationale Explanation of prediction */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $rationale = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
