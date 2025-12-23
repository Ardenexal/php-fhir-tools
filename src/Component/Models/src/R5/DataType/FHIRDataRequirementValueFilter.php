<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-complex-type DataRequirement.valueFilter
 * @description Value filters specify additional constraints on the data for elements other than code-valued or date-valued. Each value filter specifies an additional constraint on the data (i.e. valueFilters are AND'ed, not OR'ed).
 */
class FHIRDataRequirementValueFilter extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string path An attribute to filter on */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $path = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string searchParam A parameter to search on */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $searchParam = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRValueFilterComparatorType comparator eq | gt | lt | ge | le | sa | eb */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRValueFilterComparatorType $comparator = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPeriod|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDuration valueX The value of the filter, as a Period, DateTime, or Duration value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPeriod|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDuration|null $valueX = null,
	) {
		parent::__construct($id, $extension);
	}
}
