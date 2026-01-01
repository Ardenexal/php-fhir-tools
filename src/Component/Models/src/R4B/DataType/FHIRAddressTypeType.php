<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRAddressType
 * @description Code type wrapper for FHIRAddressType enum
 */
class FHIRAddressTypeType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @param \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRAddressType|string|null $value The code value (enum or string) */
		string|null $value = null,
	) {
		parent::__construct(value: $value);
	}
}
