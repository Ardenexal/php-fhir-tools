<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

/**
 * ValueSet: RestfulCapabilityMode
 * URL: http://hl7.org/fhir/ValueSet/restful-capability-mode
 * Version: 4.3.0
 * Description: The mode of a RESTful capability statement.
 */
enum RestfulCapabilityMode: string
{
    /** Client */
    case client = 'client';

    /** Server */
    case server = 'server';
}
