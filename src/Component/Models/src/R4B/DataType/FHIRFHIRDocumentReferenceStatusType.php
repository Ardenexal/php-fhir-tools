<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRDocumentReferenceStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRDocumentReferenceStatus
 *
 * @description Code type wrapper for FHIRDocumentReferenceStatus enum
 */
class FHIRFHIRDocumentReferenceStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRDocumentReferenceStatus|string|null $value The code value */
        public FHIRFHIRDocumentReferenceStatus|string|null $value = null,
    ) {
    }
}
