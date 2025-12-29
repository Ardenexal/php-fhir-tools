<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: DeviceAssociation Status Codes
 * URL: http://hl7.org/fhir/ValueSet/deviceassociation-status
 * Version: 5.0.0
 * Description: DeviceAssociation Status Codes
 */
enum FHIRDeviceAssociationCodes: string
{
	/** Implanted */
	case implanted = 'implanted';

	/** Explanted */
	case explanted = 'explanted';

	/** Entered in Error */
	case enteredinerror = 'entered-in-error';

	/** Attached */
	case attached = 'attached';

	/** Unknown */
	case unknown = 'unknown';
}
