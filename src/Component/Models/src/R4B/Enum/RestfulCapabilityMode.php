<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: RestfulCapabilityMode
 * URL: http://hl7.org/fhir/ValueSet/restful-capability-mode
 * Version: 4.3.0
 * Description: The mode of a RESTful capability statement.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/restful-capability-mode', version: '4.3.0')]
enum RestfulCapabilityMode: string
{
    /** Client */
    case client = 'client';

    /** Server */
    case server = 'server';
}
