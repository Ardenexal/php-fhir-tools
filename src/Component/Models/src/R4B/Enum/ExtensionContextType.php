<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

/**
 * ValueSet: ExtensionContextType
 * URL: http://hl7.org/fhir/ValueSet/extension-context-type
 * Version: 4.3.0
 * Description: How an extension context is interpreted.
 */
enum ExtensionContextType: string
{
	/** FHIRPath */
	case fhirpath = 'fhirpath';

	/** Element ID */
	case elementid = 'element';

	/** Extension URL */
	case extensionurl = 'extension';
}
