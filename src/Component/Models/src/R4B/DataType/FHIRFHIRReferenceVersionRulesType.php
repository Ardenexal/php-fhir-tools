<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRReferenceVersionRules
 * @description Code type wrapper for FHIRReferenceVersionRules enum
 */
class FHIRFHIRReferenceVersionRulesType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRReferenceVersionRules|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRReferenceVersionRules|string|null $value = null,
	) {
	}
}
