<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: ResponseType
 * URL: http://hl7.org/fhir/ValueSet/response-code
 * Version: 4.3.0
 * Description: The kind of response to a message.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/response-code', version: '4.3.0')]
enum ResponseType: string
{
    /** OK */
    case ok = 'ok';

    /** Transient Error */
    case transienterror = 'transient-error';

    /** Fatal Error */
    case fatalerror = 'fatal-error';
}
