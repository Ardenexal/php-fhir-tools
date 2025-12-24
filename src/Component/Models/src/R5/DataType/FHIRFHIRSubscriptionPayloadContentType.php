<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRSubscriptionPayloadContent;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRSubscriptionPayloadContent
 *
 * @description Code type wrapper for FHIRSubscriptionPayloadContent enum
 */
class FHIRFHIRSubscriptionPayloadContentType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRSubscriptionPayloadContent|string|null $value The code value */
        public FHIRFHIRSubscriptionPayloadContent|string|null $value = null,
    ) {
    }
}
