<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Restful Capability Mode
 * URL: http://hl7.org/fhir/ValueSet/restful-capability-mode
 * Version: 5.0.0
 * Description: The mode of a RESTful capability statement.
 */
enum RestfulCapabilityMode: string
{
	/** Client */
	case client = 'client';

	/** Server */
	case server = 'server';
}
