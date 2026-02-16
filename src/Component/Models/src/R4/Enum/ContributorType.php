<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: ContributorType
 * URL: http://hl7.org/fhir/ValueSet/contributor-type
 * Version: 4.0.1
 * Description: The type of contributor.
 */
enum ContributorType: string
{
    /** Author */
    case author = 'author';

    /** Editor */
    case editor = 'editor';

    /** Reviewer */
    case reviewer = 'reviewer';

    /** Endorser */
    case endorser = 'endorser';
}
