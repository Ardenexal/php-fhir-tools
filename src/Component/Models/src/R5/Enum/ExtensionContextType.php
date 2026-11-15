<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Extension Context Type
 * URL: http://hl7.org/fhir/ValueSet/extension-context-type
 * Version: 5.0.0
 * Description: How an extension context is interpreted.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/extension-context-type', version: '5.0.0')]
enum ExtensionContextType: string
{
    /** FHIRPath */
    case fhirpath = 'fhirpath';

    /** Element ID */
    case elementid = 'element';

    /** Extension URL */
    case extensionurl = 'extension';
}
