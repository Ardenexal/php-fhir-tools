<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: NarrativeStatus
 * URL: http://hl7.org/fhir/ValueSet/narrative-status
 * Version: 4.0.1
 * Description: The status of a resource narrative.
 */
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
