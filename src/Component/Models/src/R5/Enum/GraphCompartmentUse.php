<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Graph Compartment Use
 * URL: http://hl7.org/fhir/ValueSet/graph-compartment-use
 * Version: 5.0.0
 * Description: Defines how a compartment rule is used.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/graph-compartment-use', version: '5.0.0')]
enum GraphCompartmentUse: string
{
    /** Where */
    case where = 'where';

    /** requires */
    case requires = 'requires';
}
