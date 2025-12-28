<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRDocumentReferenceStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRDocumentReferenceStatus
 *
 * @description Code type wrapper for FHIRDocumentReferenceStatus enum
 */
class FHIRDocumentReferenceStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRDocumentReferenceStatus|string|null $value The code value */
        public FHIRDocumentReferenceStatus|string|null $value = null,
    ) {
    }
}
