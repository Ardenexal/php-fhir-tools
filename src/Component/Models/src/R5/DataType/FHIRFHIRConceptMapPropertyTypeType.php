<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRConceptMapPropertyType
 * @description Code type wrapper for FHIRConceptMapPropertyType enum
 */
class FHIRFHIRConceptMapPropertyTypeType extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRConceptMapPropertyType|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRConceptMapPropertyType|string|null $value = null,
	) {
	}
}
