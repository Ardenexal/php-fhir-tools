<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRDeviceCorrectiveActionScope
 * @description Code type wrapper for FHIRDeviceCorrectiveActionScope enum
 */
class FHIRDeviceCorrectiveActionScopeType extends \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRDeviceCorrectiveActionScope|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRDeviceCorrectiveActionScope|string|null $value = null,
	) {
	}
}
