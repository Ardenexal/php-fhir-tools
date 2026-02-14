<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: GraphCompartmentRule
 * URL: http://hl7.org/fhir/ValueSet/graph-compartment-rule
 * Version: 4.0.1
 * Description: How a compartment must be linked.
 */
enum GraphCompartmentRule: string
{
    /** Identical */
    case identical = 'identical';

    /** Matching */
    case matching = 'matching';

    /** Different */
    case different = 'different';

    /** Custom */
    case custom = 'custom';
}
