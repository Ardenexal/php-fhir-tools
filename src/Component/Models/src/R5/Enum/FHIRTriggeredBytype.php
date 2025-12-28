<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: triggered Bytype
 * URL: http://hl7.org/fhir/ValueSet/observation-triggeredbytype
 * Version: 5.0.0
 * Description: Codes providing the type of triggeredBy observation.
 */
enum FHIRTriggeredBytype: string
{
    /** Reflex */
    case reflex = 'reflex';

    /** Repeat (per policy) */
    case repeatperpolicy = 'repeat';

    /** Re-run (per policy) */
    case rerunperpolicy = 're-run';
}
