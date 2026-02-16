<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: Status
 * URL: http://hl7.org/fhir/ValueSet/verificationresult-status
 * Version: 4.0.1
 * Description: The validation status of the target
 */
enum Status: string
{
    /** Attested */
    case attested = 'attested';

    /** Validated */
    case validated = 'validated';

    /** In process */
    case inprocess = 'in-process';

    /** Requires revalidation */
    case requiresrevalidation = 'req-revalid';

    /** Validation failed */
    case validationfailed = 'val-fail';

    /** Re-Validation failed */
    case revalidationfailed = 'reval-fail';
}
