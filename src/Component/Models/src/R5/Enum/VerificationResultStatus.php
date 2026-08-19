<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Verification Result status
 * URL: http://hl7.org/fhir/ValueSet/verificationresult-status
 * Version: 5.0.0
 * Description: The validation status of the target
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/verificationresult-status', version: '5.0.0')]
enum VerificationResultStatus: string
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

    /** Entered in Error */
    case enteredinerror = 'entered-in-error';
}
