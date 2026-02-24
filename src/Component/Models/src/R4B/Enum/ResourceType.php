<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

/**
 * ValueSet: ResourceType
 * URL: http://hl7.org/fhir/ValueSet/resource-types
 * Version: 4.3.0
 * Description: One of the resource types defined as part of this version of FHIR.
 */
enum ResourceType: string
{
    /** Resource */
    case resource = 'Resource';
}
