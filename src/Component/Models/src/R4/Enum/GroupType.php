<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: GroupType
 * URL: http://hl7.org/fhir/ValueSet/group-type
 * Version: 4.0.1
 * Description: Types of resources that are part of group.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/group-type', version: '4.0.1')]
enum GroupType: string
{
    /** Person */
    case person = 'person';

    /** Animal */
    case animal = 'animal';

    /** Practitioner */
    case practitioner = 'practitioner';

    /** Device */
    case device = 'device';

    /** Medication */
    case medication = 'medication';

    /** Substance */
    case substance = 'substance';
}
