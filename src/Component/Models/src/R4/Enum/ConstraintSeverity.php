<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: ConstraintSeverity
 * URL: http://hl7.org/fhir/ValueSet/constraint-severity
 * Version: 4.0.1
 * Description: SHALL applications comply with this constraint?
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/constraint-severity', version: '4.0.1')]
enum ConstraintSeverity: string
{
    /** Error */
    case error = 'error';

    /** Warning */
    case warning = 'warning';
}
