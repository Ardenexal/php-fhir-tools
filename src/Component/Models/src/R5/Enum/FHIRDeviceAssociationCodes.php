<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: DeviceAssociation Status Reason Codes
 * URL: http://hl7.org/fhir/ValueSet/deviceassociation-status-reason
 * Version: 5.0.0
 * Description: DeviceAssociation Status Reason Codes
 */
enum FHIRDeviceAssociationCodes: string
{
	/** Attached */
	case attached = 'attached';

	/** Disconnected */
	case disconnected = 'disconnected';

	/** Failed */
	case failed = 'failed';

	/** placed */
	case placed = 'placed';

	/** Replaced */
	case replaced = 'replaced';
}
