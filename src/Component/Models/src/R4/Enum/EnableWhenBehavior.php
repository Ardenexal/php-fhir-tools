<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: EnableWhenBehavior
 * URL: http://hl7.org/fhir/ValueSet/questionnaire-enable-behavior
 * Version: 4.0.1
 * Description: Controls how multiple enableWhen values are interpreted -  whether all or any must be true.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/questionnaire-enable-behavior', version: '4.0.1')]
enum EnableWhenBehavior: string
{
    /** All */
    case all = 'all';

    /** Any */
    case any = 'any';
}
