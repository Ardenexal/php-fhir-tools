<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRAssertionResponseTypes
 * @description Code type wrapper for FHIRAssertionResponseTypes enum
 */
class FHIRAssertionResponseTypesType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRAssertionResponseTypes|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRAssertionResponseTypes|string|null $value = null,
	) {
	}
}
