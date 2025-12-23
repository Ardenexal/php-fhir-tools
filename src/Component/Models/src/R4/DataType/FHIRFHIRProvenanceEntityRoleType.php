<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-code-type FHIRProvenanceEntityRole
 * @description Code type wrapper for FHIRProvenanceEntityRole enum
 */
class FHIRFHIRProvenanceEntityRoleType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRProvenanceEntityRole|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRProvenanceEntityRole|string|null $value = null,
	) {
	}
}
