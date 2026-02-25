<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type DeviceDefinitionRegulatoryIdentifierType
 * @description Code type wrapper for DeviceDefinitionRegulatoryIdentifierType enum
 */
class DeviceDefinitionRegulatoryIdentifierTypeType extends \Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive
{
	public function __construct(
		/** @param \Ardenexal\FHIRTools\Component\Models\R5\Enum\DeviceDefinitionRegulatoryIdentifierType|string|null $value The code value (enum or string) */
		string|null $value = null,
	) {
		parent::__construct(value: $value);
	}
}
