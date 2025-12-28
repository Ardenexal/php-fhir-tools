<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIREvidenceVariableHandling;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIREvidenceVariableHandling
 *
 * @description Code type wrapper for FHIREvidenceVariableHandling enum
 */
class FHIREvidenceVariableHandlingType extends FHIRCode
{
    public function __construct(
        /** @var FHIREvidenceVariableHandling|string|null $value The code value */
        public FHIREvidenceVariableHandling|string|null $value = null,
    ) {
    }
}
