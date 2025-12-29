<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

/**
 * ValueSet: NamingSystemType
 * URL: http://hl7.org/fhir/ValueSet/namingsystem-type
 * Version: 4.3.0
 * Description: Identifies the purpose of the naming system.
 */
enum FHIRNamingSystemType: string
{
	/** Code System */
	case codesystem = 'codesystem';

	/** Identifier */
	case identifier = 'identifier';

	/** Root */
	case root = 'root';
}
