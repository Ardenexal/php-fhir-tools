<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Clinical Use Definition Type
 * URL: http://hl7.org/fhir/ValueSet/clinical-use-definition-type
 * Version: 5.0.0
 * Description: Overall defining type of this clinical use definition.
 */
enum ClinicalUseDefinitionType: string
{
    /** Indication */
    case indication = 'indication';

    /** Contraindication */
    case contraindication = 'contraindication';

    /** Interaction */
    case interaction = 'interaction';

    /** Undesirable Effect */
    case undesirableeffect = 'undesirable-effect';

    /** Warning */
    case warning = 'warning';
}
