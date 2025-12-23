<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIREpisodeOfCareStatus
 * @description Code type wrapper for FHIREpisodeOfCareStatus enum
 */
class FHIRFHIREpisodeOfCareStatusType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIREpisodeOfCareStatus|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIREpisodeOfCareStatus|string|null $value = null,
	) {
	}
}
