<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRCapabilityStatementKind
 * @description Code type wrapper for FHIRCapabilityStatementKind enum
 */
class FHIRCapabilityStatementKindType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRCapabilityStatementKind|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRCapabilityStatementKind|string|null $value = null,
	) {
	}
}
