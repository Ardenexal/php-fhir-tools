<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element MeasureReport.group.population
 * @description The populations that make up the population group, one for each type of population appropriate for the measure.
 */
class FHIRMeasureReportGroupPopulation extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept code initial-population | numerator | numerator-exclusion | denominator | denominator-exclusion | denominator-exception | measure-population | measure-population-exclusion | measure-observation */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRInteger count Size of the population */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRInteger $count = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference subjectResults For subject-list reports, the subject results in this population */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference $subjectResults = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
