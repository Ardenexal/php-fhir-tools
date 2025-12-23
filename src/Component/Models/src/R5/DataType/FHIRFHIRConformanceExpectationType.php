<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRConformanceExpectation
 * @description Code type wrapper for FHIRConformanceExpectation enum
 */
class FHIRFHIRConformanceExpectationType extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRConformanceExpectation|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRConformanceExpectation|string|null $value = null,
	) {
	}
}
