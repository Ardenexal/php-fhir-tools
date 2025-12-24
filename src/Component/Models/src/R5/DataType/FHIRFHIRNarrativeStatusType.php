<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRNarrativeStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRNarrativeStatus
 *
 * @description Code type wrapper for FHIRNarrativeStatus enum
 */
class FHIRFHIRNarrativeStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRNarrativeStatus|string|null $value The code value */
        public FHIRFHIRNarrativeStatus|string|null $value = null,
    ) {
    }
}
