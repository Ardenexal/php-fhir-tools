<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Financial Resource Status Codes
 * URL: http://hl7.org/fhir/ValueSet/fm-status
 * Version: 5.0.0
 * Description: This value set includes Status codes.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/fm-status', version: '5.0.0')]
enum FinancialResourceStatusCodes: string
{
    /** Active */
    case active = 'active';

    /** Cancelled */
    case cancelled = 'cancelled';

    /** Draft */
    case draft = 'draft';

    /** Entered in Error */
    case enteredinerror = 'entered-in-error';
}
