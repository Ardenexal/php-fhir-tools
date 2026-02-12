<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-code-type DeviceNameType
 * @description Code type wrapper for DeviceNameType enum
 */
class DeviceNameTypeType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive
{
	public function __construct(
		/** @param \Ardenexal\FHIRTools\Component\Models\R4\Enum\DeviceNameType|string|null $value The code value (enum or string) */
		string|null $value = null,
	) {
		parent::__construct(value: $value);
	}
}
