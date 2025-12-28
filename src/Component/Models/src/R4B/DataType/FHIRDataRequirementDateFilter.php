<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;

/**
 * @description Date filters specify additional constraints on the data in terms of the applicable date range for specific elements. Each date filter specifies an additional constraint on the data, i.e. date filters are AND'ed, not OR'ed.
 */
#[FHIRComplexType(typeName: 'DataRequirement.dateFilter', fhirVersion: 'R4B')]
class FHIRDataRequirementDateFilter extends FHIRElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var FHIRString|string|null path A date-valued attribute to filter on */
        public \FHIRString|string|null $path = null,
        /** @var FHIRString|string|null searchParam A date valued parameter to search on */
        public \FHIRString|string|null $searchParam = null,
        /** @var FHIRDateTime|FHIRPeriod|FHIRDuration|null valueX The value of the filter, as a Period, DateTime, or Duration value */
        public \FHIRDateTime|\FHIRPeriod|\FHIRDuration|null $valueX = null,
    ) {
        parent::__construct($id, $extension);
    }
}
