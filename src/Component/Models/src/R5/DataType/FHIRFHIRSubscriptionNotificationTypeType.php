<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRSubscriptionNotificationType
 * @description Code type wrapper for FHIRSubscriptionNotificationType enum
 */
class FHIRFHIRSubscriptionNotificationTypeType extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRFHIRSubscriptionNotificationType|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRFHIRSubscriptionNotificationType|string|null $value = null,
	) {
	}
}
