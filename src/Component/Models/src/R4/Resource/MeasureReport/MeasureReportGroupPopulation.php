<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MeasureReport;

/**
 * @description The populations that make up the population group, one for each type of population appropriate for the measure.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'MeasureReport', elementPath: 'MeasureReport.group.population', fhirVersion: 'R4')]
class MeasureReportGroupPopulation extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept code initial-population | numerator | numerator-exclusion | denominator | denominator-exclusion | denominator-exception | measure-population | measure-population-exclusion | measure-observation */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $code = null,
		/** @var null|int count Size of the population */
		public ?int $count = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference subjectResults For subject-list reports, the subject results in this population */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $subjectResults = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
