<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Invoice Status
 * URL: http://hl7.org/fhir/ValueSet/invoice-status
 * Version: 5.0.0
 * Description: Codes identifying the lifecycle stage of an Invoice.
 */
enum InvoiceStatus: string
{
    /** draft */
    case draft = 'draft';

    /** issued */
    case issued = 'issued';

    /** balanced */
    case balanced = 'balanced';

    /** cancelled */
    case cancelled = 'cancelled';

    /** entered in error */
    case enteredinerror = 'entered-in-error';
}
