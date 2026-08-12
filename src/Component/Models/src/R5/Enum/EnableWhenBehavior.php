<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Enable When Behavior
 * URL: http://hl7.org/fhir/ValueSet/questionnaire-enable-behavior
 * Version: 5.0.0
 * Description: Controls how multiple enableWhen values are interpreted -  whether all or any must be true.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/questionnaire-enable-behavior', version: '5.0.0')]
enum EnableWhenBehavior: string
{
    /** All */
    case all = 'all';

    /** Any */
    case any = 'any';
}
