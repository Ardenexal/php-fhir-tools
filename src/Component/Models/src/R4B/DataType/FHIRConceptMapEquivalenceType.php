<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRConceptMapEquivalence
 * @description Code type wrapper for FHIRConceptMapEquivalence enum
 */
class FHIRConceptMapEquivalenceType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRConceptMapEquivalence|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRConceptMapEquivalence|string|null $value = null,
	) {
	}
}
