<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRConstraintSeverity
 * @description Code type wrapper for FHIRConstraintSeverity enum
 */
class FHIRFHIRConstraintSeverityType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRConstraintSeverity|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRConstraintSeverity|string|null $value = null,
	) {
	}
}
