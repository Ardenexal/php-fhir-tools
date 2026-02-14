<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: IdentityAssuranceLevel
 * URL: http://hl7.org/fhir/ValueSet/identity-assuranceLevel
 * Version: 4.0.1
 * Description: The level of confidence that this link represents the same actual person, based on NIST Authentication Levels.
 */
enum IdentityAssuranceLevel: string
{
    /** Level 1 */
    case level1 = 'level1';

    /** Level 2 */
    case level2 = 'level2';

    /** Level 3 */
    case level3 = 'level3';

    /** Level 4 */
    case level4 = 'level4';
}
