<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRDetectedIssueSeverity
 * @description Code type wrapper for FHIRDetectedIssueSeverity enum
 */
class FHIRDetectedIssueSeverityType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRDetectedIssueSeverity|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRDetectedIssueSeverity|string|null $value = null,
	) {
	}
}
