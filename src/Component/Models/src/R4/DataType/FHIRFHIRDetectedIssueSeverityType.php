<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-code-type FHIRDetectedIssueSeverity
 * @description Code type wrapper for FHIRDetectedIssueSeverity enum
 */
class FHIRFHIRDetectedIssueSeverityType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRDetectedIssueSeverity|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRDetectedIssueSeverity|string|null $value = null,
	) {
	}
}
