<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: BindingStrength
 * URL: http://hl7.org/fhir/ValueSet/binding-strength
 * Version: 4.0.1
 * Description: Indication of the degree of conformance expectations associated with a binding.
 */
enum BindingStrength: string
{
    /** Required */
    case required = 'required';

    /** Extensible */
    case extensible = 'extensible';

    /** Preferred */
    case preferred = 'preferred';

    /** Example */
    case example = 'example';
}
