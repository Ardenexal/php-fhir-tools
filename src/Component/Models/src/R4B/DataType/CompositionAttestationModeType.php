<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\CompositionAttestationMode;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @fhir-code-type CompositionAttestationMode
 *
 * @description Code type wrapper for CompositionAttestationMode enum
 */
class CompositionAttestationModeType extends CodePrimitive
{
    public function __construct(
        /** @param CompositionAttestationMode|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
