<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Type Derivation Rule
 * URL: http://hl7.org/fhir/ValueSet/type-derivation-rule
 * Version: 5.0.0
 * Description: How a type relates to its baseDefinition.
 */
enum TypeDerivationRule: string
{
    /** Specialization */
    case specialization = 'specialization';

    /** Constraint */
    case constraint = 'constraint';
}
