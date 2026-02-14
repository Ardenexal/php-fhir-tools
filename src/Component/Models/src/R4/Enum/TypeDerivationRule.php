<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: TypeDerivationRule
 * URL: http://hl7.org/fhir/ValueSet/type-derivation-rule
 * Version: 4.0.1
 * Description: How a type relates to its baseDefinition.
 */
enum TypeDerivationRule: string
{
    /** Specialization */
    case specialization = 'specialization';

    /** Constraint */
    case constraint = 'constraint';
}
