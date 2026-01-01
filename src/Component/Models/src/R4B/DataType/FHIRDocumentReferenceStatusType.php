<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

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
        /** @param FHIRDocumentReferenceStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
