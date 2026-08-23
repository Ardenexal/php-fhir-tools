<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: ContributorType
 * URL: http://hl7.org/fhir/ValueSet/contributor-type
 * Version: 5.0.0
 * Description: The type of contributor.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/contributor-type', version: '5.0.0')]
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
