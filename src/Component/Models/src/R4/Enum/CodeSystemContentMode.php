<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: CodeSystemContentMode
 * URL: http://hl7.org/fhir/ValueSet/codesystem-content-mode
 * Version: 4.0.1
 * Description: The extent of the content of the code system (the concepts and codes it defines) are represented in a code system resource.
 */
enum CodeSystemContentMode: string
{
    /** Not Present */
    case notpresent = 'not-present';

    /** Example */
    case example = 'example';

    /** Fragment */
    case fragment = 'fragment';

    /** Complete */
    case complete = 'complete';

    /** Supplement */
    case supplement = 'supplement';
}
