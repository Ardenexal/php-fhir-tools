<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-code-type FHIRQuantityComparator
 * @description Code type wrapper for FHIRQuantityComparator enum
 */
class FHIRFHIRQuantityComparatorType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRQuantityComparator|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRQuantityComparator|string|null $value = null,
	) {
	}
}
