<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRReportRelationshipType
 * @description Code type wrapper for FHIRReportRelationshipType enum
 */
class FHIRFHIRReportRelationshipTypeType extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRFHIRReportRelationshipType|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRFHIRReportRelationshipType|string|null $value = null,
	) {
	}
}
