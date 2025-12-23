<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRVersionIndependentResourceTypesAll
 * @description Code type wrapper for FHIRVersionIndependentResourceTypesAll enum
 */
class FHIRFHIRVersionIndependentResourceTypesAllType extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRVersionIndependentResourceTypesAll|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRVersionIndependentResourceTypesAll|string|null $value = null,
	) {
	}
}
