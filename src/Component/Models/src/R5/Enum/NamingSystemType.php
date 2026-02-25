<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Naming System Type
 * URL: http://hl7.org/fhir/ValueSet/namingsystem-type
 * Version: 5.0.0
 * Description: Identifies the purpose of the naming system.
 */
enum NamingSystemType: string
{
	/** Code System */
	case codesystem = 'codesystem';

	/** Identifier */
	case identifier = 'identifier';

	/** Root */
	case root = 'root';
}
