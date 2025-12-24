<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: ConstraintSeverity
 * URL: http://hl7.org/fhir/ValueSet/constraint-severity
 * Version: 4.0.1
 * Description: SHALL applications comply with this constraint?
 */
enum FHIRConstraintSeverity: string
{
    /** Error */
    case error = 'error';

    /** Warning */
    case warning = 'warning';
}
