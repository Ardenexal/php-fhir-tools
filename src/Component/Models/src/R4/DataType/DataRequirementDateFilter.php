<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @description Date filters specify additional constraints on the data in terms of the applicable date range for specific elements. Each date filter specifies an additional constraint on the data, i.e. date filters are AND'ed, not OR'ed.
 */
#[FHIRComplexType(typeName: 'DataRequirement.dateFilter', fhirVersion: 'R4')]
class DataRequirementDateFilter extends Element
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var StringPrimitive|string|null path A date-valued attribute to filter on */
        public StringPrimitive|string|null $path = null,
        /** @var StringPrimitive|string|null searchParam A date valued parameter to search on */
        public StringPrimitive|string|null $searchParam = null,
        /** @var DateTimePrimitive|Period|Duration|null valueX The value of the filter, as a Period, DateTime, or Duration value */
        public DateTimePrimitive|Period|Duration|null $valueX = null,
    ) {
        parent::__construct($id, $extension);
    }
}
