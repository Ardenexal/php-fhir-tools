<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: NamingSystemType
 * URL: http://hl7.org/fhir/ValueSet/namingsystem-type
 * Version: 4.0.1
 * Description: Identifies the purpose of the naming system.
 */
enum NamingSystemType: string
{
    /** Code System */
    case codesystem = 'codesystem';

    /** Identifier */
    case identifier = 'identifier';

    /** Root */
    case root = 'root';
}
