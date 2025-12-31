<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @description Date filters specify additional constraints on the data in terms of the applicable date range for specific elements. Each date filter specifies an additional constraint on the data, i.e. date filters are AND'ed, not OR'ed.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'DataRequirement.dateFilter', fhirVersion: 'R4')]
class FHIRDataRequirementDateFilter extends FHIRElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string path A date-valued attribute to filter on */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string|null $path = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string searchParam A date valued parameter to search on */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string|null $searchParam = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRPeriod|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRDuration valueX The value of the filter, as a Period, DateTime, or Duration value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime|FHIRPeriod|FHIRDuration|null $valueX = null,
	) {
		parent::__construct($id, $extension);
	}
}
