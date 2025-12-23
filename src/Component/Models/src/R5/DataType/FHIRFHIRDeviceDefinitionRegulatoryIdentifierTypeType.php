<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRDeviceDefinitionRegulatoryIdentifierType
 * @description Code type wrapper for FHIRDeviceDefinitionRegulatoryIdentifierType enum
 */
class FHIRFHIRDeviceDefinitionRegulatoryIdentifierTypeType extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRDeviceDefinitionRegulatoryIdentifierType|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRDeviceDefinitionRegulatoryIdentifierType|string|null $value = null,
	) {
	}
}
