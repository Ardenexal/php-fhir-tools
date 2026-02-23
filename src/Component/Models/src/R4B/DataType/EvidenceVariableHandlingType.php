<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\EvidenceVariableHandling;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @fhir-code-type EvidenceVariableHandling
 *
 * @description Code type wrapper for EvidenceVariableHandling enum
 */
class EvidenceVariableHandlingType extends CodePrimitive
{
    public function __construct(
        /** @param EvidenceVariableHandling|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
