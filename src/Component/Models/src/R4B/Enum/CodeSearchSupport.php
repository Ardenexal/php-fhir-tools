<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: CodeSearchSupport
 * URL: http://hl7.org/fhir/ValueSet/code-search-support
 * Version: 4.3.0
 * Description: The degree to which the server supports the code search parameter on ValueSet, if it is supported.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/code-search-support', version: '4.3.0')]
enum CodeSearchSupport: string
{
    /** Explicit Codes */
    case explicitcodes = 'explicit';

    /** Implicit Codes */
    case implicitcodes = 'all';
}
