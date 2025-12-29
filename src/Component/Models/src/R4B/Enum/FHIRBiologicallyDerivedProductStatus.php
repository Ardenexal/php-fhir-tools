<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

/**
 * ValueSet: BiologicallyDerivedProductStatus
 * URL: http://hl7.org/fhir/ValueSet/product-status
 * Version: 4.3.0
 * Description: Biologically Derived Product Status.
 */
enum FHIRBiologicallyDerivedProductStatus: string
{
	/** Available */
	case available = 'available';

	/** Unavailable */
	case unavailable = 'unavailable';
}
