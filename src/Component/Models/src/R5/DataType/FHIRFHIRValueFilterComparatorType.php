<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRValueFilterComparator
 * @description Code type wrapper for FHIRValueFilterComparator enum
 */
class FHIRFHIRValueFilterComparatorType extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRValueFilterComparator|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRValueFilterComparator|string|null $value = null,
	) {
	}
}
