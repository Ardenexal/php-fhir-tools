<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

/**
 * ValueSet: SlicingRules
 * URL: http://hl7.org/fhir/ValueSet/resource-slicing-rules
 * Version: 4.3.0
 * Description: How slices are interpreted when evaluating an instance.
 */
enum SlicingRules: string
{
    /** Closed */
    case closed = 'closed';

    /** Open */
    case open = 'open';

    /** Open at End */
    case openatend = 'openAtEnd';
}
