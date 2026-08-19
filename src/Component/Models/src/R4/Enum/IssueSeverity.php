<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: IssueSeverity
 * URL: http://hl7.org/fhir/ValueSet/issue-severity
 * Version: 4.0.1
 * Description: How the issue affects the success of the action.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/issue-severity', version: '4.0.1')]
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
