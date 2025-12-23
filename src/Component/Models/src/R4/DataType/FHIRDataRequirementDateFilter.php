<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-complex-type DataRequirement.dateFilter
 * @description Date filters specify additional constraints on the data in terms of the applicable date range for specific elements. Each date filter specifies an additional constraint on the data, i.e. date filters are AND'ed, not OR'ed.
 */
class FHIRDataRequirementDateFilter extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string path A date-valued attribute to filter on */
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|null $path = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string searchParam A date valued parameter to search on */
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|null $searchParam = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRPeriod|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDuration valueX The value of the filter, as a Period, DateTime, or Duration value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRPeriod|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDuration|null $valueX = null,
	) {
		parent::__construct($id, $extension);
	}
}
