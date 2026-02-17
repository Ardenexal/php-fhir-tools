<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: GraphCompartmentUse
 * URL: http://hl7.org/fhir/ValueSet/graph-compartment-use
 * Version: 4.0.1
 * Description: Defines how a compartment rule is used.
 */
enum GraphCompartmentUse: string
{
    /** Condition */
    case condition = 'condition';

    /** Requirement */
    case requirement = 'requirement';
}
