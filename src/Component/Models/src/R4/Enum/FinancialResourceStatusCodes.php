<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: Financial Resource Status Codes
 * URL: http://hl7.org/fhir/ValueSet/fm-status
 * Version: 4.0.1
 * Description: This value set includes Status codes.
 */
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
