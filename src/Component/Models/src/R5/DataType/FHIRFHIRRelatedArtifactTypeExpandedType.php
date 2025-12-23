<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRRelatedArtifactTypeExpanded
 * @description Code type wrapper for FHIRRelatedArtifactTypeExpanded enum
 */
class FHIRFHIRRelatedArtifactTypeExpandedType extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRRelatedArtifactTypeExpanded|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRRelatedArtifactTypeExpanded|string|null $value = null,
	) {
	}
}
