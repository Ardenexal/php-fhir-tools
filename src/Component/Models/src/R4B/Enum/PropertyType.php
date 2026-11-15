<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: PropertyType
 * URL: http://hl7.org/fhir/ValueSet/concept-property-type
 * Version: 4.3.0
 * Description: The type of a property value.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/concept-property-type', version: '4.3.0')]
enum PropertyType: string
{
    /** code (internal reference) */
    case codeinternalreference = 'code';

    /** Coding (external reference) */
    case codingexternalreference = 'Coding';

    /** string */
    case string = 'string';

    /** integer */
    case integer = 'integer';

    /** boolean */
    case boolean = 'boolean';

    /** dateTime */
    case datetime = 'dateTime';

    /** decimal */
    case decimal = 'decimal';
}
