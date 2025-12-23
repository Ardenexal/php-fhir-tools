<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRDeviceCorrectiveActionScope
 * @description Code type wrapper for FHIRDeviceCorrectiveActionScope enum
 */
class FHIRFHIRDeviceCorrectiveActionScopeType extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRDeviceCorrectiveActionScope|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRDeviceCorrectiveActionScope|string|null $value = null,
	) {
	}
}
