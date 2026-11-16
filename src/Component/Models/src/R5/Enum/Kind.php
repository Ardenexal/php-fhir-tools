<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Kind
 * URL: http://hl7.org/fhir/ValueSet/coverage-kind
 * Version: 5.0.0
 * Description: The kind of coverage: insurance, selfpay or other.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/coverage-kind', version: '5.0.0')]
enum Kind: string
{
    /** Insurance */
    case insurance = 'insurance';

    /** Self-pay */
    case selfpay = 'self-pay';

    /** Other */
    case other = 'other';
}
