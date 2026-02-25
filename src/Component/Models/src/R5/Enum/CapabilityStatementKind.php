<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Capability Statement Kind
 * URL: http://hl7.org/fhir/ValueSet/capability-statement-kind
 * Version: 5.0.0
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
