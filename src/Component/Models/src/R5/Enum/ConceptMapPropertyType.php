<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: ConceptMap Property Type
 * URL: http://hl7.org/fhir/ValueSet/conceptmap-property-type
 * Version: 5.0.0
 * Description: The type of a ConceptMap mapping property value.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/conceptmap-property-type', version: '5.0.0')]
enum ConceptMapPropertyType: string
{
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

    /** code */
    case code = 'code';
}
