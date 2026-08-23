<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: NameUse
 * URL: http://hl7.org/fhir/ValueSet/name-use
 * Version: 5.0.0
 * Description: The use of a human name.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/name-use', version: '5.0.0')]
enum NameUse: string
{
    /** Usual */
    case usual = 'usual';

    /** Official */
    case official = 'official';

    /** Temp */
    case temp = 'temp';

    /** Nickname */
    case nickname = 'nickname';

    /** Anonymous */
    case anonymous = 'anonymous';

    /** Old */
    case old = 'old';

    /** Name changed for Marriage */
    case namechangedformarriage = 'maiden';
}
