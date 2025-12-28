<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRFHIREvidenceVariableHandling;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIREvidenceVariableHandling
 *
 * @description Code type wrapper for FHIREvidenceVariableHandling enum
 */
class FHIRFHIREvidenceVariableHandlingType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIREvidenceVariableHandling|string|null $value The code value */
        public FHIRFHIREvidenceVariableHandling|string|null $value = null,
    ) {
    }
}
