<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element MeasureReport.group.population
 * @description The populations that make up the population group, one for each type of population appropriate for the measure.
 */
class FHIRMeasureReportGroupPopulation extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string linkId Pointer to specific population from Measure */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $linkId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept code initial-population | numerator | numerator-exclusion | denominator | denominator-exclusion | denominator-exception | measure-population | measure-population-exclusion | measure-observation */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRInteger count Size of the population */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRInteger $count = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference subjectResults For subject-list reports, the subject results in this population */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference $subjectResults = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> subjectReport For subject-list reports, a subject result in this population */
		public array $subjectReport = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference subjects What individual(s) in the population */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference $subjects = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
