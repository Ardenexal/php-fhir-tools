<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRSubscriptionPayloadContent;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRSubscriptionPayloadContent
 *
 * @description Code type wrapper for FHIRSubscriptionPayloadContent enum
 */
class FHIRSubscriptionPayloadContentType extends FHIRCode
{
    public function __construct(
        /** @var FHIRSubscriptionPayloadContent|string|null $value The code value */
        public FHIRSubscriptionPayloadContent|string|null $value = null,
    ) {
    }
}
