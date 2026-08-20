<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: ExtensionContextType
 * URL: http://hl7.org/fhir/ValueSet/extension-context-type
 * Version: 4.0.1
 * Description: How an extension context is interpreted.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/extension-context-type', version: '4.0.1')]
enum ExtensionContextType: string
{
    /** FHIRPath */
    case fhirpath = 'fhirpath';

    /** Element ID */
    case elementid = 'element';

    /** Extension URL */
    case extensionurl = 'extension';
}
