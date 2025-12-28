<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

/**
 * ValueSet: AggregationMode
 * URL: http://hl7.org/fhir/ValueSet/resource-aggregation-mode
 * Version: 4.0.1
 * Description: How resource references can be aggregated.
 */
enum FHIRAggregationMode: string
{
    /** Contained */
    case contained = 'contained';

    /** Referenced */
    case referenced = 'referenced';
}
