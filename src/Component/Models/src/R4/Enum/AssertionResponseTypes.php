<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: AssertionResponseTypes
 * URL: http://hl7.org/fhir/ValueSet/assert-response-code-types
 * Version: 4.0.1
 * Description: The type of response code to use for assertion.
 */
enum AssertionResponseTypes: string
{
    /** okay */
    case okay = 'okay';

    /** created */
    case created = 'created';

    /** noContent */
    case nocontent = 'noContent';

    /** notModified */
    case notmodified = 'notModified';

    /** bad */
    case bad = 'bad';

    /** forbidden */
    case forbidden = 'forbidden';

    /** notFound */
    case notfound = 'notFound';

    /** methodNotAllowed */
    case methodnotallowed = 'methodNotAllowed';

    /** conflict */
    case conflict = 'conflict';

    /** gone */
    case gone = 'gone';

    /** preconditionFailed */
    case preconditionfailed = 'preconditionFailed';

    /** unprocessable */
    case unprocessable = 'unprocessable';
}
