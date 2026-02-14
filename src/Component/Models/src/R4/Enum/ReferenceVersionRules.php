<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: ReferenceVersionRules
 * URL: http://hl7.org/fhir/ValueSet/reference-version-rules
 * Version: 4.0.1
 * Description: Whether a reference needs to be version specific or version independent, or whether either can be used.
 */
enum ReferenceVersionRules: string
{
    /** Either Specific or independent */
    case eitherspecificorindependent = 'either';

    /** Version independent */
    case versionindependent = 'independent';

    /** Version Specific */
    case versionspecific = 'specific';
}
