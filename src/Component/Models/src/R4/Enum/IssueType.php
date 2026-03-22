<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: IssueType
 * URL: http://hl7.org/fhir/ValueSet/issue-type
 * Version: 4.0.1
 * Description: A code that describes the type of issue.
 */
enum IssueType: string
{
    /** Invalid Content */
    case invalidcontent = 'invalid';

    /** Security Problem */
    case securityproblem = 'security';

    /** Processing Failure */
    case processingfailure = 'processing';

    /** Transient Issue */
    case transientissue = 'transient';

    /** Informational Note */
    case informationalnote = 'informational';
}
