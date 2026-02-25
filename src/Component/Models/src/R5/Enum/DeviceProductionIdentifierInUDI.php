<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Device Production Identifier In UDI
 * URL: http://hl7.org/fhir/ValueSet/device-productidentifierinudi
 * Version: 5.0.0
 * Description: Device Production Identifier in UDI
 */
enum DeviceProductionIdentifierInUDI: string
{
	/** Lot Number */
	case lotnumber = 'lot-number';

	/** Manufactured date */
	case manufactureddate = 'manufactured-date';

	/** Serial Number */
	case serialnumber = 'serial-number';

	/** Expiration date */
	case expirationdate = 'expiration-date';

	/** Biological source */
	case biologicalsource = 'biological-source';

	/** Software Version */
	case softwareversion = 'software-version';
}
