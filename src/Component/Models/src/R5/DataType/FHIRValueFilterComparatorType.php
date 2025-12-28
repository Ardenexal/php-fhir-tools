<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRValueFilterComparator
 * @description Code type wrapper for FHIRValueFilterComparator enum
 */
class FHIRValueFilterComparatorType extends \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRValueFilterComparator|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRValueFilterComparator|string|null $value = null,
	) {
	}
}
