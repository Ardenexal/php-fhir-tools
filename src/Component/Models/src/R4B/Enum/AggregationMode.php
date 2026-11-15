<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: AggregationMode
 * URL: http://hl7.org/fhir/ValueSet/resource-aggregation-mode
 * Version: 4.3.0
 * Description: How resource references can be aggregated.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/resource-aggregation-mode', version: '4.3.0')]
enum AggregationMode: string
{
    /** Contained */
    case contained = 'contained';

    /** Referenced */
    case referenced = 'referenced';

    /** Bundled */
    case bundled = 'bundled';
}
