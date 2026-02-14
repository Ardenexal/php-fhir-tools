<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MeasureReport;

/**
 * @description This element contains the results for a single stratum within the stratifier. For example, when stratifying on administrative gender, there will be four strata, one for each possible gender value.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'MeasureReport', elementPath: 'MeasureReport.group.stratifier.stratum', fhirVersion: 'R4')]
class MeasureReportGroupStratifierStratum extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept value The stratum value, e.g. male */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $value = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\MeasureReport\MeasureReportGroupStratifierStratumComponent> component Stratifier component values */
		public array $component = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\MeasureReport\MeasureReportGroupStratifierStratumPopulation> population Population results in this stratum */
		public array $population = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity measureScore What score this stratum achieved */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity $measureScore = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
