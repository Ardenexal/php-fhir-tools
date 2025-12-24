<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRTypeRestfulInteraction;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRTypeRestfulInteraction
 *
 * @description Code type wrapper for FHIRTypeRestfulInteraction enum
 */
class FHIRFHIRTypeRestfulInteractionType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRTypeRestfulInteraction|string|null $value The code value */
        public FHIRFHIRTypeRestfulInteraction|string|null $value = null,
    ) {
    }
}
