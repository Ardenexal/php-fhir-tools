<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @description The results of the calculation, one for each population group in the measure.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'MeasureReport', elementPath: 'MeasureReport.group', fhirVersion: 'R4B')]
class FHIRMeasureReportGroup extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept code Meaning of the group */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept $code = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMeasureReportGroupPopulation> population The populations in the group */
		public array $population = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRQuantity measureScore What score this group achieved */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRQuantity $measureScore = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMeasureReportGroupStratifier> stratifier Stratification results */
		public array $stratifier = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
