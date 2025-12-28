<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRSystemRestfulInteraction;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRSystemRestfulInteraction
 *
 * @description Code type wrapper for FHIRSystemRestfulInteraction enum
 */
class FHIRFHIRSystemRestfulInteractionType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRSystemRestfulInteraction|string|null $value The code value */
        public FHIRFHIRSystemRestfulInteraction|string|null $value = null,
    ) {
    }
}
