<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: UDI Entry Type
 * URL: http://hl7.org/fhir/ValueSet/udi-entry-type
 * Version: 5.0.0
 * Description: Codes to identify how UDI data was entered.
 */
enum UDIEntryType: string
{
	/** Barcode */
	case barcode = 'barcode';

	/** RFID */
	case rfid = 'rfid';

	/** Manual */
	case manual = 'manual';

	/** Card */
	case card = 'card';

	/** Self Reported */
	case selfreported = 'self-reported';

	/** Electronic Transmission */
	case electronictransmission = 'electronic-transmission';

	/** Unknown */
	case unknown = 'unknown';
}
