<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: PropertyRepresentation
 * URL: http://hl7.org/fhir/ValueSet/property-representation
 * Version: 5.0.0
 * Description: How a property is represented when serialized.
 */
enum PropertyRepresentation: string
{
    /** XML Attribute */
    case xmlattribute = 'xmlAttr';

    /** XML Text */
    case xmltext = 'xmlText';

    /** Type Attribute */
    case typeattribute = 'typeAttr';

    /** CDA Text Format */
    case cdatextformat = 'cdaText';

    /** XHTML */
    case xhtml = 'xhtml';
}
