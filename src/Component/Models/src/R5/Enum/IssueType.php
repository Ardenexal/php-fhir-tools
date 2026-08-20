<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Issue Type
 * URL: http://hl7.org/fhir/ValueSet/issue-type
 * Version: 5.0.0
 * Description: A code that describes the type of issue.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/issue-type', version: '5.0.0')]
enum IssueType: string
{
    /** Invalid Content */
    case invalidcontent = 'invalid';

    /** Structural Issue */
    case structuralissue = 'structure';

    /** Required element missing */
    case requiredelementmissing = 'required';

    /** Element value invalid */
    case elementvalueinvalid = 'value';

    /** Validation rule failed */
    case validationrulefailed = 'invariant';

    /** Security Problem */
    case securityproblem = 'security';

    /** Login Required */
    case loginrequired = 'login';

    /** Unknown User */
    case unknownuser = 'unknown';

    /** Session Expired */
    case sessionexpired = 'expired';

    /** Forbidden */
    case forbidden = 'forbidden';

    /** Information  Suppressed */
    case informationsuppressed = 'suppressed';

    /** Processing Failure */
    case processingfailure = 'processing';

    /** Content not supported */
    case contentnotsupported = 'not-supported';

    /** Duplicate */
    case duplicate = 'duplicate';

    /** Multiple Matches */
    case multiplematches = 'multiple-matches';

    /** Not Found */
    case notfound = 'not-found';

    /** Deleted */
    case deleted = 'deleted';

    /** Content Too Long */
    case contenttoolong = 'too-long';

    /** Invalid Code */
    case invalidcode = 'code-invalid';

    /** Unacceptable Extension */
    case unacceptableextension = 'extension';

    /** Operation Too Costly */
    case operationtoocostly = 'too-costly';

    /** Business Rule Violation */
    case businessruleviolation = 'business-rule';

    /** Edit Version Conflict */
    case editversionconflict = 'conflict';

    /** Limited Filter Application */
    case limitedfilterapplication = 'limited-filter';

    /** Transient Issue */
    case transientissue = 'transient';

    /** Lock Error */
    case lockerror = 'lock-error';

    /** No Store Available */
    case nostoreavailable = 'no-store';

    /** Exception */
    case exception = 'exception';

    /** Timeout */
    case timeout = 'timeout';

    /** Incomplete Results */
    case incompleteresults = 'incomplete';

    /** Throttled */
    case throttled = 'throttled';

    /** Informational Note */
    case informationalnote = 'informational';

    /** Operation Successful */
    case operationsuccessful = 'success';
}
