<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: HTTPVerb
 * URL: http://hl7.org/fhir/ValueSet/http-verb
 * Version: 4.0.1
 * Description: HTTP verbs (in the HTTP command line). See [HTTP rfc](https://tools.ietf.org/html/rfc7231) for details.
 */
enum HTTPVerb: string
{
    /** GET */
    case get = 'GET';

    /** HEAD */
    case head = 'HEAD';

    /** POST */
    case post = 'POST';

    /** PUT */
    case put = 'PUT';

    /** DELETE */
    case delete = 'DELETE';

    /** PATCH */
    case patch = 'PATCH';
}
