<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: TypeDerivationRule
 * URL: http://hl7.org/fhir/ValueSet/type-derivation-rule
 * Version: 4.0.1
 * Description: How a type relates to its baseDefinition.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/type-derivation-rule', version: '4.0.1')]
enum TypeDerivationRule: string
{
    /** Specialization */
    case specialization = 'specialization';

    /** Constraint */
    case constraint = 'constraint';
}
