<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRSubmitDataUpdateType
 * @description Code type wrapper for FHIRSubmitDataUpdateType enum
 */
class FHIRSubmitDataUpdateTypeType extends \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRSubmitDataUpdateType|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRSubmitDataUpdateType|string|null $value = null,
	) {
	}
}
