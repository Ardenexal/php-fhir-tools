<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: TestScriptRequestMethodCode
 * URL: http://hl7.org/fhir/ValueSet/http-operations
 * Version: 4.3.0
 * Description: The allowable request method or HTTP operation codes.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/http-operations', version: '4.3.0')]
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
