<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Questionnaire Item Disabled Display
 * URL: http://hl7.org/fhir/ValueSet/questionnaire-disabled-display
 * Version: 5.0.0
 * Description: Codes that guide the display of disabled questionnaire items
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/questionnaire-disabled-display', version: '5.0.0')]
enum QuestionnaireItemDisabledDisplay: string
{
    /** Hidden */
    case hidden = 'hidden';

    /** Protected */
    case protected = 'protected';
}
