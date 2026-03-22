<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: IssueSeverity
 * URL: http://hl7.org/fhir/ValueSet/issue-severity
 * Version: 4.0.1
 * Description: How the issue affects the success of the action.
 */
enum IssueSeverity: string
{
    /** Fatal */
    case fatal = 'fatal';

    /** Error */
    case error = 'error';

    /** Warning */
    case warning = 'warning';

    /** Information */
    case information = 'information';
}
