<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Response Type
 * URL: http://hl7.org/fhir/ValueSet/response-code
 * Version: 5.0.0
 * Description: The kind of response to a message.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/response-code', version: '5.0.0')]
enum ResponseType: string
{
    /** OK */
    case ok = 'ok';

    /** Transient Error */
    case transienterror = 'transient-error';

    /** Fatal Error */
    case fatalerror = 'fatal-error';
}
