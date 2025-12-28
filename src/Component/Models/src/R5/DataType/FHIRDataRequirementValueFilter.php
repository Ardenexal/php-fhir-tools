<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;

/**
 * @description Value filters specify additional constraints on the data for elements other than code-valued or date-valued. Each value filter specifies an additional constraint on the data (i.e. valueFilters are AND'ed, not OR'ed).
 */
#[FHIRComplexType(typeName: 'DataRequirement.valueFilter', fhirVersion: 'R5')]
class FHIRDataRequirementValueFilter extends FHIRElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var FHIRString|string|null path An attribute to filter on */
        public \FHIRString|string|null $path = null,
        /** @var FHIRString|string|null searchParam A parameter to search on */
        public \FHIRString|string|null $searchParam = null,
        /** @var FHIRValueFilterComparatorType|null comparator eq | gt | lt | ge | le | sa | eb */
        public ?\FHIRValueFilterComparatorType $comparator = null,
        /** @var FHIRDateTime|FHIRPeriod|FHIRDuration|null valueX The value of the filter, as a Period, DateTime, or Duration value */
        public \FHIRDateTime|\FHIRPeriod|\FHIRDuration|null $valueX = null,
    ) {
        parent::__construct($id, $extension);
    }
}
