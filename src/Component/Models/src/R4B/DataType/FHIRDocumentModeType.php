<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRDocumentMode;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRDocumentMode
 *
 * @description Code type wrapper for FHIRDocumentMode enum
 */
class FHIRDocumentModeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRDocumentMode|string|null $value The code value */
        public FHIRDocumentMode|string|null $value = null,
    ) {
    }
}
