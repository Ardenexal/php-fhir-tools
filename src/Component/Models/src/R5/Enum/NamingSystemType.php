<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Naming System Type
 * URL: http://hl7.org/fhir/ValueSet/namingsystem-type
 * Version: 5.0.0
 * Description: Identifies the purpose of the naming system.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/namingsystem-type', version: '5.0.0')]
enum NamingSystemType: string
{
    /** Code System */
    case codesystem = 'codesystem';

    /** Identifier */
    case identifier = 'identifier';

    /** Root */
    case root = 'root';
}
