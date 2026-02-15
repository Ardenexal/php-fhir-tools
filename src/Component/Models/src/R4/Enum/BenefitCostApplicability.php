<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: Benefit cost applicability
 * URL: http://hl7.org/fhir/ValueSet/insuranceplan-applicability
 * Version: 4.0.1
 * Description: Whether the cost applies to in-network or out-of-network providers.
 */
enum BenefitCostApplicability: string
{
    /** In Network */
    case innetwork = 'in-network';

    /** Out of Network */
    case outofnetwork = 'out-of-network';

    /** Other */
    case other = 'other';
}
