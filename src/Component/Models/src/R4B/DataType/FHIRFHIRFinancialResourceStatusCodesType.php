<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRFinancialResourceStatusCodes
 * @description Code type wrapper for FHIRFinancialResourceStatusCodes enum
 */
class FHIRFHIRFinancialResourceStatusCodesType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRFinancialResourceStatusCodes|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRFinancialResourceStatusCodes|string|null $value = null,
	) {
	}
}
