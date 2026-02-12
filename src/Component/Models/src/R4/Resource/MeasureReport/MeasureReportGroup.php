<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MeasureReport;

/**
 * @description The results of the calculation, one for each population group in the measure.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'MeasureReport', elementPath: 'MeasureReport.group', fhirVersion: 'R4')]
class MeasureReportGroup extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept code Meaning of the group */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $code = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\MeasureReport\MeasureReportGroupPopulation> population The populations in the group */
		public array $population = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity measureScore What score this group achieved */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity $measureScore = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\MeasureReport\MeasureReportGroupStratifier> stratifier Stratification results */
		public array $stratifier = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
