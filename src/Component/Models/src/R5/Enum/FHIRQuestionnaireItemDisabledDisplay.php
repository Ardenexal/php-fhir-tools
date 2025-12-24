<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Questionnaire Item Disabled Display
 * URL: http://hl7.org/fhir/ValueSet/questionnaire-disabled-display
 * Version: 5.0.0
 * Description: Codes that guide the display of disabled questionnaire items
 */
enum FHIRQuestionnaireItemDisabledDisplay: string
{
    /** Hidden */
    case hidden = 'hidden';

    /** Protected */
    case protected = 'protected';
}
