<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRPropertyRepresentation
 * @description Code type wrapper for FHIRPropertyRepresentation enum
 */
class FHIRFHIRPropertyRepresentationType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRPropertyRepresentation|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRPropertyRepresentation|string|null $value = null,
	) {
	}
}
