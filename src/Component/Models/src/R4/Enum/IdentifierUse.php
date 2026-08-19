<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: IdentifierUse
 * URL: http://hl7.org/fhir/ValueSet/identifier-use
 * Version: 4.0.1
 * Description: Identifies the purpose for this identifier, if known .
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/identifier-use', version: '4.0.1')]
enum IdentifierUse: string
{
    /** Usual */
    case usual = 'usual';

    /** Official */
    case official = 'official';

    /** Temp */
    case temp = 'temp';

    /** Secondary */
    case secondary = 'secondary';

    /** Old */
    case old = 'old';
}
