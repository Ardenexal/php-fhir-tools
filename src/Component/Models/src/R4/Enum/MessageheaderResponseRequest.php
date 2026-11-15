<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: messageheader-response-request
 * URL: http://hl7.org/fhir/ValueSet/messageheader-response-request
 * Version: 4.0.1
 * Description: HL7-defined table of codes which identify conditions under which acknowledgments are required to be returned in response to a message.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/messageheader-response-request', version: '4.0.1')]
enum MessageheaderResponseRequest: string
{
    /** Always */
    case always = 'always';

    /** Error/reject conditions only */
    case errorrejectconditionsonly = 'on-error';

    /** Never */
    case never = 'never';

    /** Successful completion only */
    case successfulcompletiononly = 'on-success';
}
