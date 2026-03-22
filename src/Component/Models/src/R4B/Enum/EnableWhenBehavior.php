<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

/**
 * ValueSet: EnableWhenBehavior
 * URL: http://hl7.org/fhir/ValueSet/questionnaire-enable-behavior
 * Version: 4.3.0
 * Description: Controls how multiple enableWhen values are interpreted -  whether all or any must be true.
 */
enum EnableWhenBehavior: string
{
    /** All */
    case all = 'all';

    /** Any */
    case any = 'any';
}
