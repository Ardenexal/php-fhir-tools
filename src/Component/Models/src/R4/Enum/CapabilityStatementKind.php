<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: CapabilityStatementKind
 * URL: http://hl7.org/fhir/ValueSet/capability-statement-kind
 * Version: 4.0.1
 * Description: How a capability statement is intended to be used.
 */
enum CapabilityStatementKind: string
{
    /** Instance */
    case instance = 'instance';

    /** Capability */
    case capability = 'capability';

    /** Requirements */
    case requirements = 'requirements';
}
