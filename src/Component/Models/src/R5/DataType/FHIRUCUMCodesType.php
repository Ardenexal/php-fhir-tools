<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRUCUMCodes
 * @description Code type wrapper for FHIRUCUMCodes enum
 */
class FHIRUCUMCodesType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @param \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRUCUMCodes|string|null $value The code value (enum or string) */
		string|null $value = null,
	) {
		parent::__construct(value: $value);
	}
}
