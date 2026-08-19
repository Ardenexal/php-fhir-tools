<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: triggered Bytype
 * URL: http://hl7.org/fhir/ValueSet/observation-triggeredbytype
 * Version: 5.0.0
 * Description: Codes providing the type of triggeredBy observation.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/observation-triggeredbytype', version: '5.0.0')]
enum TriggeredBytype: string
{
    /** Reflex */
    case reflex = 'reflex';

    /** Repeat (per policy) */
    case repeatperpolicy = 'repeat';

    /** Re-run (per policy) */
    case rerunperpolicy = 're-run';
}
