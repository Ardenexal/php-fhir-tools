<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: DiscriminatorType
 * URL: http://hl7.org/fhir/ValueSet/discriminator-type
 * Version: 4.0.1
 * Description: How an element value is interpreted when discrimination is evaluated.
 */
enum DiscriminatorType: string
{
    /** Value */
    case value = 'value';

    /** Exists */
    case exists = 'exists';

    /** Pattern */
    case pattern = 'pattern';

    /** Type */
    case type = 'type';

    /** Profile */
    case profile = 'profile';
}
