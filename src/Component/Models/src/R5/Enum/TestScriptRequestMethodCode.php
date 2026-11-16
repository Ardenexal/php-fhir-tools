<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Test Script Request Method Code
 * URL: http://hl7.org/fhir/ValueSet/http-operations
 * Version: 5.0.0
 * Description: The allowable request method or HTTP operation codes.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/http-operations', version: '5.0.0')]
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
