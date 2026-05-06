<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Graph Compartment Rule
 * URL: http://hl7.org/fhir/ValueSet/graph-compartment-rule
 * Version: 5.0.0
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
