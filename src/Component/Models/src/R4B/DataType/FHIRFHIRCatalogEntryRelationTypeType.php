<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRCatalogEntryRelationType
 * @description Code type wrapper for FHIRCatalogEntryRelationType enum
 */
class FHIRFHIRCatalogEntryRelationTypeType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRCatalogEntryRelationType|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRCatalogEntryRelationType|string|null $value = null,
	) {
	}
}
