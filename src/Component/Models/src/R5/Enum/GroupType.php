<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Group Type
 * URL: http://hl7.org/fhir/ValueSet/group-type
 * Version: 5.0.0
 * Description: Types of resources that are part of group.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/group-type', version: '5.0.0')]
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

    /** CareTeam */
    case careteam = 'careteam';

    /** HealthcareService */
    case healthcareservice = 'healthcareservice';

    /** Location */
    case location = 'location';

    /** Organization */
    case organization = 'organization';

    /** RelatedPerson */
    case relatedperson = 'relatedperson';

    /** Specimen */
    case specimen = 'specimen';
}
