<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Allergy Intolerance Category
 * URL: http://hl7.org/fhir/ValueSet/allergy-intolerance-category
 * Version: 5.0.0
 * Description: Category of an identified substance associated with allergies or intolerances.
 */
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
