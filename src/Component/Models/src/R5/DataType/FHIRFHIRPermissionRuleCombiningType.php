<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRPermissionRuleCombining
 * @description Code type wrapper for FHIRPermissionRuleCombining enum
 */
class FHIRFHIRPermissionRuleCombiningType extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRPermissionRuleCombining|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRPermissionRuleCombining|string|null $value = null,
	) {
	}
}
