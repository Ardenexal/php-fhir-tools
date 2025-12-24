<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRNoteType;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRNoteType
 *
 * @description Code type wrapper for FHIRNoteType enum
 */
class FHIRFHIRNoteTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRNoteType|string|null $value The code value */
        public FHIRFHIRNoteType|string|null $value = null,
    ) {
    }
}
