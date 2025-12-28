<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRSystemRestfulInteraction;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRSystemRestfulInteraction
 *
 * @description Code type wrapper for FHIRSystemRestfulInteraction enum
 */
class FHIRSystemRestfulInteractionType extends FHIRCode
{
    public function __construct(
        /** @var FHIRSystemRestfulInteraction|string|null $value The code value */
        public FHIRSystemRestfulInteraction|string|null $value = null,
    ) {
    }
}
