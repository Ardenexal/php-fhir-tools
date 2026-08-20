<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: ConsentDataMeaning
 * URL: http://hl7.org/fhir/ValueSet/consent-data-meaning
 * Version: 4.3.0
 * Description: How a resource reference is interpreted when testing consent restrictions.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/consent-data-meaning', version: '4.3.0')]
enum ConsentDataMeaning: string
{
    /** Instance */
    case instance = 'instance';

    /** Related */
    case related = 'related';

    /** Dependents */
    case dependents = 'dependents';

    /** AuthoredBy */
    case authoredby = 'authoredby';
}
