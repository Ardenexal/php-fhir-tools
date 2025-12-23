<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: FormularyItem Status Codes
 * URL: http://hl7.org/fhir/ValueSet/formularyitem-status
 * Version: 5.0.0
 * Description: FormularyItem Status Codes
 */
enum FHIRFormularyItemStatusCodes: string
{
	/** Active */
	case active = 'active';

	/** Entered in Error */
	case enteredinerror = 'entered-in-error';

	/** Inactive */
	case inactive = 'inactive';
}
