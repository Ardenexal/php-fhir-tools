<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRTypeRestfulInteraction;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRTypeRestfulInteraction
 *
 * @description Code type wrapper for FHIRTypeRestfulInteraction enum
 */
class FHIRTypeRestfulInteractionType extends FHIRCode
{
    public function __construct(
        /** @var FHIRTypeRestfulInteraction|string|null $value The code value */
        public FHIRTypeRestfulInteraction|string|null $value = null,
    ) {
    }
}
