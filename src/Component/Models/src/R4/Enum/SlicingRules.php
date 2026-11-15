<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: SlicingRules
 * URL: http://hl7.org/fhir/ValueSet/resource-slicing-rules
 * Version: 4.0.1
 * Description: How slices are interpreted when evaluating an instance.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/resource-slicing-rules', version: '4.0.1')]
enum SlicingRules: string
{
    /** Closed */
    case closed = 'closed';

    /** Open */
    case open = 'open';

    /** Open at End */
    case openatend = 'openAtEnd';
}
