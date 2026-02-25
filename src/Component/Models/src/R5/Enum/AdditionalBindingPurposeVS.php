<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Additional Binding Purpose ValueSet
 * URL: http://hl7.org/fhir/ValueSet/additional-binding-purpose
 * Version: 5.0.0
 * Description: Additional Binding Purpose
 */
enum AdditionalBindingPurposeVS: string
{
    /** Maximum Binding */
    case maximumbinding = 'maximum';

    /** Minimum Binding */
    case minimumbinding = 'minimum';

    /** Required Binding */
    case requiredbinding = 'required';

    /** Conformance Binding */
    case conformancebinding = 'extensible';

    /** Candidate Binding */
    case candidatebinding = 'candidate';

    /** Current Binding */
    case currentbinding = 'current';

    /** Preferred Binding */
    case preferredbinding = 'preferred';

    /** UI Suggested Binding */
    case uisuggestedbinding = 'ui';

    /** Starter Binding */
    case starterbinding = 'starter';

    /** Component Binding */
    case componentbinding = 'component';
}
