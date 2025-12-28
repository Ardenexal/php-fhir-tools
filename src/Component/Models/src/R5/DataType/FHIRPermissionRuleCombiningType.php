<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRPermissionRuleCombining
 * @description Code type wrapper for FHIRPermissionRuleCombining enum
 */
class FHIRPermissionRuleCombiningType extends \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRPermissionRuleCombining|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRPermissionRuleCombining|string|null $value = null,
	) {
	}
}
