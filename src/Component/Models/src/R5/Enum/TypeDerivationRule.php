<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Type Derivation Rule
 * URL: http://hl7.org/fhir/ValueSet/type-derivation-rule
 * Version: 5.0.0
 * Description: How a type relates to its baseDefinition.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/type-derivation-rule', version: '5.0.0')]
enum TypeDerivationRule: string
{
    /** Specialization */
    case specialization = 'specialization';

    /** Constraint */
    case constraint = 'constraint';
}
