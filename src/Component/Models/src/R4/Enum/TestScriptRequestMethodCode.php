<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: TestScriptRequestMethodCode
 * URL: http://hl7.org/fhir/ValueSet/http-operations
 * Version: 4.0.1
 * Description: The allowable request method or HTTP operation codes.
 */
enum TestScriptRequestMethodCode: string
{
    /** DELETE */
    case delete = 'delete';

    /** GET */
    case get = 'get';

    /** OPTIONS */
    case options = 'options';

    /** PATCH */
    case patch = 'patch';

    /** POST */
    case post = 'post';

    /** PUT */
    case put = 'put';

    /** HEAD */
    case head = 'head';
}
