<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRDocumentMode;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRDocumentMode
 *
 * @description Code type wrapper for FHIRDocumentMode enum
 */
class FHIRFHIRDocumentModeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRDocumentMode|string|null $value The code value */
        public FHIRFHIRDocumentMode|string|null $value = null,
    ) {
    }
}
