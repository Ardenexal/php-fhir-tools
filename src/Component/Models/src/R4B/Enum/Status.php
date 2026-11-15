<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: status
 * URL: http://hl7.org/fhir/ValueSet/verificationresult-status
 * Version: 4.3.0
 * Description: The validation status of the target
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/verificationresult-status', version: '4.3.0')]
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
