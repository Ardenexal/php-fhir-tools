<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: ConsentDataMeaning
 * URL: http://hl7.org/fhir/ValueSet/consent-data-meaning
 * Version: 4.0.1
 * Description: How a resource reference is interpreted when testing consent restrictions.
 */
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
