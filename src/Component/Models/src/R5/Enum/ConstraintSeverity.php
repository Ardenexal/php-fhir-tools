<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: ConstraintSeverity
 * URL: http://hl7.org/fhir/ValueSet/constraint-severity
 * Version: 5.0.0
 * Description: SHALL applications comply with this constraint?
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/constraint-severity', version: '5.0.0')]
enum ConstraintSeverity: string
{
    /** Error */
    case error = 'error';

    /** Warning */
    case warning = 'warning';
}
