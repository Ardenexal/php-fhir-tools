<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type SubscriptionStatusCodes
 * @description Code type wrapper for SubscriptionStatusCodes enum
 */
class SubscriptionStatusCodesType extends \Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive
{
	public function __construct(
		/** @param \Ardenexal\FHIRTools\Component\Models\R5\Enum\SubscriptionStatusCodes|string|null $value The code value (enum or string) */
		string|null $value = null,
	) {
		parent::__construct(value: $value);
	}
}
