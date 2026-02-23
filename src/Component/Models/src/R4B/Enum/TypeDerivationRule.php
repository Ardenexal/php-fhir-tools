<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

/**
 * ValueSet: TypeDerivationRule
 * URL: http://hl7.org/fhir/ValueSet/type-derivation-rule
 * Version: 4.3.0
 * Description: How a type relates to its baseDefinition.
 */
enum TypeDerivationRule: string
{
	/** Specialization */
	case specialization = 'specialization';

	/** Constraint */
	case constraint = 'constraint';
}
