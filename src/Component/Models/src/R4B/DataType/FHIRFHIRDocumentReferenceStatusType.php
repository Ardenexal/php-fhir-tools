<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRDocumentReferenceStatus
 * @description Code type wrapper for FHIRDocumentReferenceStatus enum
 */
class FHIRFHIRDocumentReferenceStatusType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRDocumentReferenceStatus|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRDocumentReferenceStatus|string|null $value = null,
	) {
	}
}
