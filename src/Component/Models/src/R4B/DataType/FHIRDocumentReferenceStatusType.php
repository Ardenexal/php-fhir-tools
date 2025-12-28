<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRDocumentReferenceStatus
 * @description Code type wrapper for FHIRDocumentReferenceStatus enum
 */
class FHIRDocumentReferenceStatusType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRDocumentReferenceStatus|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRDocumentReferenceStatus|string|null $value = null,
	) {
	}
}
