<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-code-type FHIRReferenceVersionRules
 * @description Code type wrapper for FHIRReferenceVersionRules enum
 */
class FHIRReferenceVersionRulesType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRReferenceVersionRules|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRReferenceVersionRules|string|null $value = null,
	) {
	}
}
