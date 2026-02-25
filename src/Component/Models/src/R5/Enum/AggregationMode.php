<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: AggregationMode
 * URL: http://hl7.org/fhir/ValueSet/resource-aggregation-mode
 * Version: 5.0.0
 * Description: How resource references can be aggregated.
 */
enum AggregationMode: string
{
    /** Contained */
    case contained = 'contained';

    /** Referenced */
    case referenced = 'referenced';
}
