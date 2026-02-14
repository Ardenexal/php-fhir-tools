<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\RiskAssessment;

/**
 * @description Describes the expected outcome for the subject.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'RiskAssessment', elementPath: 'RiskAssessment.prediction', fhirVersion: 'R4')]
class RiskAssessmentPrediction extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept outcome Possible outcome for the subject */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $outcome = null,
		/** @var null|float|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Range probabilityX Likelihood of specified outcome */
		public float|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Range|null $probabilityX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept qualitativeRisk Likelihood of specified outcome as a qualitative value */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $qualitativeRisk = null,
		/** @var null|float relativeRisk Relative likelihood */
		public ?float $relativeRisk = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Range whenX Timeframe or age range */
		public \Ardenexal\FHIRTools\Component\Models\R4\DataType\Period|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Range|null $whenX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string rationale Explanation of prediction */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $rationale = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
