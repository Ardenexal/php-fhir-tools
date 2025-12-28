<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRNoteType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRNoteType
 *
 * @description Code type wrapper for FHIRNoteType enum
 */
class FHIRNoteTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRNoteType|string|null $value The code value */
        public FHIRNoteType|string|null $value = null,
    ) {
    }
}
