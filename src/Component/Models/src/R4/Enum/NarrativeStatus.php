<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: NarrativeStatus
 * URL: http://hl7.org/fhir/ValueSet/narrative-status
 * Version: 4.0.1
 * Description: The status of a resource narrative.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/narrative-status', version: '4.0.1')]
enum NarrativeStatus: string
{
    /** Generated */
    case generated = 'generated';

    /** Extensions */
    case extensions = 'extensions';

    /** Additional */
    case additional = 'additional';

    /** Empty */
    case empty = 'empty';
}
