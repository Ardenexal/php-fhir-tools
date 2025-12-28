<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRActionSelectionBehavior;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRActionSelectionBehavior
 *
 * @description Code type wrapper for FHIRActionSelectionBehavior enum
 */
class FHIRActionSelectionBehaviorType extends FHIRCode
{
    public function __construct(
        /** @var FHIRActionSelectionBehavior|string|null $value The code value */
        public FHIRActionSelectionBehavior|string|null $value = null,
    ) {
    }
}
