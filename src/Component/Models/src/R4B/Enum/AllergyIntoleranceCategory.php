<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: AllergyIntoleranceCategory
 * URL: http://hl7.org/fhir/ValueSet/allergy-intolerance-category
 * Version: 4.3.0
 * Description: Category of an identified substance associated with allergies or intolerances.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/allergy-intolerance-category', version: '4.3.0')]
enum AllergyIntoleranceCategory: string
{
    /** Food */
    case food = 'food';

    /** Medication */
    case medication = 'medication';

    /** Environment */
    case environment = 'environment';

    /** Biologic */
    case biologic = 'biologic';
}
