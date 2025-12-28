<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRTriggeredBytype
 * @description Code type wrapper for FHIRTriggeredBytype enum
 */
class FHIRTriggeredBytypeType extends \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRTriggeredBytype|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRTriggeredBytype|string|null $value = null,
	) {
	}
}
