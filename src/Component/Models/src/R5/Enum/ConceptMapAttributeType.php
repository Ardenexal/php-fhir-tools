<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: ConceptMap Attribute Type
 * URL: http://hl7.org/fhir/ValueSet/conceptmap-attribute-type
 * Version: 5.0.0
 * Description: The type of a ConceptMap mapping attribute value.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/conceptmap-attribute-type', version: '5.0.0')]
enum ConceptMapAttributeType: string
{
    /** code */
    case code = 'code';

    /** Coding */
    case coding = 'Coding';

    /** string */
    case string = 'string';

    /** boolean */
    case boolean = 'boolean';

    /** Quantity */
    case quantity = 'Quantity';
}
