<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-code-type FHIRCatalogEntryRelationType
 * @description Code type wrapper for FHIRCatalogEntryRelationType enum
 */
class FHIRCatalogEntryRelationTypeType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRCatalogEntryRelationType|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRCatalogEntryRelationType|string|null $value = null,
	) {
	}
}
