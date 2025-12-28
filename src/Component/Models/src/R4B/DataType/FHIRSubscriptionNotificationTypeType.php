<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRSubscriptionNotificationType
 * @description Code type wrapper for FHIRSubscriptionNotificationType enum
 */
class FHIRSubscriptionNotificationTypeType extends \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRSubscriptionNotificationType|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRSubscriptionNotificationType|string|null $value = null,
	) {
	}
}
