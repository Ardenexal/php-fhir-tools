<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

/**
 * ValueSet: RemittanceOutcome
 * URL: http://hl7.org/fhir/ValueSet/remittance-outcome
 * Version: 4.3.0
 * Description: The outcome of the processing.
 */
enum FHIRRemittanceOutcome: string
{
	/** Queued */
	case queued = 'queued';

	/** Complete */
	case complete = 'complete';

	/** Error */
	case error = 'error';

	/** Partial */
	case partial = 'partial';
}
