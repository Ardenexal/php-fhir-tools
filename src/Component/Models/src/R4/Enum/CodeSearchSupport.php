<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: CodeSearchSupport
 * URL: http://hl7.org/fhir/ValueSet/code-search-support
 * Version: 4.0.1
 * Description: The degree to which the server supports the code search parameter on ValueSet, if it is supported.
 */
enum CodeSearchSupport: string
{
    /** Explicit Codes */
    case explicitcodes = 'explicit';

    /** Implicit Codes */
    case implicitcodes = 'all';
}
