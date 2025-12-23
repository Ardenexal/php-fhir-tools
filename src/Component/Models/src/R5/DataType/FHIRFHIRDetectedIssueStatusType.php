<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRDetectedIssueStatus
 * @description Code type wrapper for FHIRDetectedIssueStatus enum
 */
class FHIRFHIRDetectedIssueStatusType extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRDetectedIssueStatus|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRDetectedIssueStatus|string|null $value = null,
	) {
	}
}
