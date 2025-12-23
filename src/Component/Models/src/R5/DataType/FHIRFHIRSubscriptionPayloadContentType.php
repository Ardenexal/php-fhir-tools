<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRSubscriptionPayloadContent
 * @description Code type wrapper for FHIRSubscriptionPayloadContent enum
 */
class FHIRFHIRSubscriptionPayloadContentType extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRSubscriptionPayloadContent|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRSubscriptionPayloadContent|string|null $value = null,
	) {
	}
}
